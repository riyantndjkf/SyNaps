<?php
require_once("mahasiswa.php");

if (isset($_GET["nrp"])) {
    $nrp = $_GET["nrp"];
    $mhsObj = new Mahasiswa();

    // ambil data mahasiswa (untuk tau foto_extention)
    $mhs = $mhsObj->getMahasiswa($nrp);

    if ($mhs && $mhs['foto_extention']) {
        $fotoFile = "images/" . $nrp . "." . $mhs['foto_extention'];
        if (file_exists($fotoFile)) unlink($fotoFile);
    }

    if ($mhsObj->deleteMahasiswa($nrp)) {
        echo "<script>alert('Data mahasiswa berhasil dihapus!'); window.location='mahasiswa.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data mahasiswa'); window.location='mahasiswa.php';</script>";
    }
}
?>