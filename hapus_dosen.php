<?php
require_once("dosen.php");

if (isset($_GET["npk"])) {
    $npk = $_GET["npk"];
    $dosenObj = new Dosen();

    // ambil data dosen (untuk tau foto_extension)
    $dosen = $dosenObj->getDosen($npk);

    if ($dosen && $dosen['foto_extension']) {
        $fotoFile = "images/" . $npk . "." . $dosen['foto_extension'];
        if (file_exists($fotoFile)) unlink($fotoFile);
    }

    if ($dosenObj->deleteDosen($npk)) {
        echo "<script>alert('Data dosen berhasil dihapus!'); window.location='dosen.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data dosen'); window.location='dosen.php';</script>";
    }
}
?>
