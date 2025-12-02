<?php
require_once("security.php");
require_once("class/grup.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: display_grup.php");
    exit;
}

$idgrup = $_GET['id'];
$grupObj = new Grup();
$grup = $grupObj->getGrup($idgrup);

if ($grup && $grup['username_pembuat'] == $_SESSION['username']) {
    $grupObj->deleteGrup($idgrup);
    header("Location: display_grup.php?status=deleted");
} else {
    header("Location: display_grup.php?status=error");
}
exit;
?>