<?php
require_once("class/dosen.php"); 
require_once("class/akun.php");

if (isset($_GET["npk"])) {
    $npk = $_GET["npk"];
    $dosenObj = new Dosen();
    $akunObj = new Akun();

    $dosen = $dosenObj->getDosen($npk);

    if ($dosen && $dosen['foto_extension']) {
        $fotoFile = "images/" . $npk . "." . $dosen['foto_extension'];
        if (file_exists($fotoFile)) {
            unlink($fotoFile);
        }
    }

    if ($dosenObj->deleteDosen($npk) && $akunObj->deleteAkunDosen($npk)) {
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
