<?php
require_once("security.php");
require_once("class/event.php");

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
