<?php
require_once("mahasiswa.php"); // supaya class Mahasiswa bisa dipakai

if (isset($_GET["nrp"])) {
    $nrp = $_GET["nrp"];
    $mhsObj = new Mahasiswa();

    // ambil data mahasiswa untuk tau foto_extension
    $mhs = $mhsObj->getMahasiswa($nrp);

    // hapus file foto jika ada
    if ($mhs && $mhs['foto_extension']) {
        $fotoFile = "images/" . $nrp . "." . $mhs['foto_extension'];
        if (file_exists($fotoFile)) {
            unlink($fotoFile);
        }
    }

    // hapus data dari database
    if ($mhsObj->deleteMahasiswa($nrp)) {
        header("Location: display_mahasiswa.php?status=success");
        exit;
    } else {
        header("Location: display_mahasiswa.php?status=error");
        exit;
    }
} else {
    header("Location: display_mahasiswa.php");
    exit;
}
?>
