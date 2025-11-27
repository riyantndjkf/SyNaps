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

// Jika yang dikirim adalah NRP saja (angka) atau hanya digits, coba map ke username akun
$akunObj = new Akun();
if (ctype_digit($username)) {
    $akun = $akunObj->getAkunByNrp($username);
    if ($akun) {
        $username = $akun['username'];
    } else {
        // jika tidak ada akun terkait NRP, redirect dengan error
        header("Location: tambah_member_mahasiswa.php?id=$idgrup&status=error");
        exit;
    }
}

$memberObj = new MemberGrup();

// Tambah hanya jika belum ada
if (!$memberObj->isMember($idgrup, $username)) {
    $memberObj->addMember($idgrup, $username);
}

header("Location: tambah_member_mahasiswa.php?id=$idgrup&status=added");
exit;
?>
