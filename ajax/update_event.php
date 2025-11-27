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

$idevent = $_POST['idevent'] ?? '';
$idgrup  = (int)($_POST['idgrup'] ?? 0);
$judul      = trim($_POST['judul'] ?? '');
$tanggal    = $_POST['tanggal'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$jenis      = $_POST['jenis'] ?? '';

if (empty($judul) || empty($tanggal) || empty($jenis)) {
    echo "error|validation_failed";
    exit;
}

// Ambil data lama untuk poster
$eventObj = new Event();
$oldEvent = $eventObj->getEvent($idevent);

if (!$oldEvent) {
    echo "error|event_not_found";
    exit;
}

// Cek permission - apakah dosen ini adalah member grup
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

// Hanya pembuat grup atau member dosen yang bisa edit event
if (!$isPembuat && !$isMember) {
    echo "error|unauthorized";
    exit;
}

// Generate slug
$slug = strtolower($judul);
$slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
$slug = trim($slug, '-');

$poster_extension = $oldEvent['poster_extension'];

// Cek upload baru
if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        // Hapus poster lama
        if (!empty($poster_extension)) {
            $oldFile = "../images/event/" . $oldEvent['judul_slug'] . "." . $poster_extension;
            if (file_exists($oldFile)) unlink($oldFile);
        }

        $poster_extension = $ext;
        $target_dir = "../images/event/";
        if (!is_dir($target_dir)) mkdir($target_dir);

        $newFile = $target_dir . $slug . "." . $ext;
        if (!move_uploaded_file($_FILES['poster']['tmp_name'], $newFile)) {
            echo "error|upload_failed";
            exit;
        }
    } else {
        echo "error|invalid_file_type";
        exit;
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
    echo "success|" . $idgrup;
} else {
    echo "error|db_failed";
}
?>
