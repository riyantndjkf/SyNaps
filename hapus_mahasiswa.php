<?php
require_once("class/mahasiswa.php");
require_once("class/akun.php");

if (isset($_GET["nrp"])) {
    $nrp = $_GET["nrp"];
    $mhsObj = new Mahasiswa();
    $akunObj = new Akun();

    $mhs = $mhsObj->getMahasiswa($nrp);

    if ($mhs && $mhs['foto_extention']) {
        $fotoFile = "images/" . $nrp . "." . $mhs['foto_extention'];
        if (file_exists($fotoFile)) {
            unlink($fotoFile);
        }
    }

    if ($mhsObj->deleteMahasiswa($nrp) && $akunObj->deleteAkunMahasiswa($nrp)) {
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
