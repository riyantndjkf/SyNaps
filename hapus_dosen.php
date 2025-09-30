<?php
require_once("dosen.php"); // supaya class Dosen bisa dipakai

if (isset($_GET["npk"])) {
    $npk = $_GET["npk"];
    $dosenObj = new Dosen();

    // ambil data dosen untuk tau foto_extension
    $dosen = $dosenObj->getDosen($npk);

    // hapus file foto jika ada
    if ($dosen && $dosen['foto_extension']) {
        $fotoFile = "images/" . $npk . "." . $dosen['foto_extension'];
        if (file_exists($fotoFile)) {
            unlink($fotoFile);
        }
    }

    // hapus data dari database
    if ($dosenObj->deleteDosen($npk)) {
    header("Location: display_dosen.php?status=success");
    exit;
} else {
    header("Location: display_dosen.php?status=error");
    exit;
}

} else {
    header("Location: display_dosen.php");
    exit;
    
}
?>
