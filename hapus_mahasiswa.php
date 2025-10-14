<?php
require_once("class/mahasiswa.php");

if (isset($_GET["nrp"])) {
    $nrp = $_GET["nrp"];
    $mhsObj = new Mahasiswa();

    $mhs = $mhsObj->getMahasiswa($nrp);

    if ($mhs && $mhs['foto_extension']) {
        $fotoFile = "images/" . $nrp . "." . $mhs['foto_extension'];
        if (file_exists($fotoFile)) {
            unlink($fotoFile);
        }
    }

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
