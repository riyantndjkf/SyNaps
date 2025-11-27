<?php
require_once("../security.php");
require_once("../class/event.php");
require_once("../class/grup.php");
require_once("../class/member_grup.php");

if (empty($_SESSION['npk_dosen'])) {
    echo "error|unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "error|invalid_method";
    exit;
}

// Ambil data dari form
$idgrup      = (int)($_POST['idgrup'] ?? 0);
$judul       = trim($_POST['judul'] ?? '');
$tanggal     = $_POST['tanggal'] ?? '';
$keterangan  = $_POST['keterangan'] ?? '';
$jenis       = $_POST['jenis'] ?? '';

// Validasi minimal
if (empty($judul) || empty($tanggal) || empty($jenis) || empty($idgrup)) {
    echo "error|validation_failed";
    exit;
}

// Cek apakah dosen ini adalah member grup (termasuk pembuat grup)
$grupObj = new Grup();
$grup = $grupObj->getGrup($idgrup);

if (!$grup) {
    echo "error|grup_not_found";
    exit;
}

$memberObj = new MemberGrup();
$username_dosen = $_SESSION['username'];
$isPembuat = ($grup['username_pembuat'] == $_SESSION['username']);
$isMember = $memberObj->isMember($idgrup, $username_dosen);

// Hanya pembuat grup atau member dosen yang bisa membuat event
if (!$isPembuat && !$isMember) {
    echo "error|unauthorized";
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
        if (!is_dir("../" . $target_dir)) mkdir("../" . $target_dir);

        $target_file = "../" . $target_dir . $slug . "." . $ext;

        if (!move_uploaded_file($_FILES['poster']['tmp_name'], $target_file)) {
            echo "error|upload_failed";
            exit;
        }
    } else {
        echo "error|invalid_file_type";
        exit;
    }
}

// Insert ke database
$eventObj = new Event();
$arr_data = [
    'idgrup' => $idgrup,
    'judul' => $judul,
    'judul_slug' => $slug,
    'tanggal' => $tanggal,
    'jenis' => $jenis,
    'keterangan' => $keterangan,
    'poster_extension' => $poster_extension
];

if ($eventObj->insertEvent($arr_data)) {
    echo "success|" . $idgrup;
} else {
    echo "error|db_failed";
}
?>
