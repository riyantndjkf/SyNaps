<?php
require_once("security.php");
require_once("class/event.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: display_grup.php");
    exit;
}

// Ambil data dari form
$idgrup      = $_POST['idgrup'];
$judul       = trim($_POST['judul']);
$tanggal     = $_POST['tanggal'];
$keterangan  = $_POST['keterangan'];
$jenis       = $_POST['jenis'];

// Validasi minimal
if (empty($judul) || empty($tanggal) || empty($jenis)) {
    header("Location: tambah_event.php?id=$idgrup&status=empty");
    exit;
}

// Generate slug
$slug = strtolower($judul);
$slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
$slug = trim($slug, '-');

// Proses upload poster (opsional)
$poster_extension = null;

if (isset($_FILES['poster']) && $_FILES['poster']['error'] === 0) {

    $allowed_ext = ['jpg', 'jpeg', 'png'];

    $ext = strtolower(pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed_ext)) {

        $poster_extension = $ext;
        $target_dir = "images/event/";
        if (!is_dir($target_dir)) mkdir($target_dir);

        // Poster filename pakai slug agar unik
        $target_file = $target_dir . $slug . "." . $ext;

        move_uploaded_file($_FILES['poster']['tmp_name'], $target_file);
    }
}

$eventObj = new Event();

$arr = [
    'idgrup'           => $idgrup,
    'judul'            => $judul,
    'judul_slug'       => $slug,
    'tanggal'          => $tanggal,
    'keterangan'       => $keterangan,
    'jenis'            => $jenis,
    'poster_extension' => $poster_extension
];

// Insert ke database
if ($eventObj->insertEvent($arr)) {
    header("Location: detail_grup.php?id=$idgrup&status=success");
    exit;
} else {
    header("Location: detail_grup.php?id=$idgrup&status=error");
    exit;
}

?>
