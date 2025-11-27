<?php
require_once("security.php");
require_once("class/event.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['grup'])) {
    header("Location: index.php");
    exit;
}

$idevent = $_GET['id'];
$idgrup  = $_GET['grup'];

$eventObj = new Event();

// Ambil data event dulu
$event = $eventObj->getEvent($idevent);

// Check permission: only pembuat grup or dosen member can delete
$grupObj = new Grup();
$grup = $grupObj->getGrup($idgrup);
$memberObj = new MemberGrup();
$username = $_SESSION['username'] ?? '';
$isPembuat = ($grup && $grup['username_pembuat'] == $username);
$isDosenMember = !empty($_SESSION['npk_dosen']) && $memberObj->isMember($idgrup, $username);
if (!$isPembuat && !$isDosenMember) {
    header("Location: detail_grup.php?id=$idgrup&status=unauthorized");
    exit;
}

if ($event) {
    // Hapus poster jika ada
    if (!empty($event['poster_extension'])) {

        $posterPath = "images/event/" . $event['judul_slug'] . "." . $event['poster_extension'];

        if (file_exists($posterPath)) {
            unlink($posterPath);
        }
    }

    // Hapus dari database
    if ($eventObj->deleteEvent($idevent)) {
        header("Location: detail_grup.php?id=$idgrup&status=event_deleted");
        exit;
    }
}

// Jika gagal
header("Location: detail_grup.php?id=$idgrup&status=error");
exit;

?>
