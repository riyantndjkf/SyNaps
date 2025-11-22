<?php
require_once("security.php");
require_once("class/event.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

$idevent = $_POST['idevent'];
$idgrup  = $_POST['idgrup'];

$judul      = trim($_POST['judul']);
$tanggal    = $_POST['tanggal'];
$keterangan = $_POST['keterangan'];
$jenis      = $_POST['jenis'];

if (empty($judul) || empty($tanggal) || empty($jenis)) {
    header("Location: update_event.php?id=$idevent&status=empty");
    exit;
}

// generate slug
$slug = strtolower($judul);
$slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
$slug = trim($slug, '-');

// ambil data lama untuk poster
$eventObj = new Event();
$oldEvent = $eventObj->getEvent($idevent);

$poster_extension = $oldEvent['poster_extension'];

// cek upload baru
if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {

    $allowed = ['jpg','jpeg','png'];
    $ext = strtolower(pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {

        // hapus poster lama
        if (!empty($poster_extension)) {
            $oldFile = "images/event/" . $oldEvent['judul_slug'] . "." . $poster_extension;
            if (file_exists($oldFile)) unlink($oldFile);
        }

        $poster_extension = $ext;
        $target_dir = "images/event/";
        if (!is_dir($target_dir)) mkdir($target_dir);

        $newFile = $target_dir . $slug . "." . $ext;
        move_uploaded_file($_FILES['poster']['tmp_name'], $newFile);
    }
}

$updateArr = [
    "judul"            => $judul,
    "judul_slug"       => $slug,
    "tanggal"          => $tanggal,
    "keterangan"       => $keterangan,
    "jenis"            => $jenis,
    "poster_extension" => $poster_extension
];

if ($eventObj->updateEvent($idevent, $updateArr)) {
    header("Location: detail_grup.php?id=$idgrup&status=success");
    exit;
} else {
    header("Location: detail_grup.php?id=$idgrup&status=error");
    exit;
}
?>
