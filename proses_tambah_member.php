<?php
require_once("security.php");
require_once("class/member_grup.php");
require_once("class/akun.php");

if (!isset($_GET['grup']) || !isset($_GET['user'])) {
    header("Location: index.php");
    exit;
}

$idgrup = $_GET['grup'];
$username = $_GET['user'];
$akunObj = new Akun();
if (ctype_digit($username)) {
    $akun = $akunObj->getAkunByNrp($username);
    if ($akun) {
        $username = $akun['username'];
    } else {
        header("Location: tambah_member_mahasiswa.php?id=$idgrup&status=error");
        exit;
    }
}

$memberObj = new MemberGrup();

if (!$memberObj->isMember($idgrup, $username)) {
    $memberObj->addMember($idgrup, $username);
}

header("Location: tambah_member_mahasiswa.php?id=$idgrup&status=added");
exit;
?>
