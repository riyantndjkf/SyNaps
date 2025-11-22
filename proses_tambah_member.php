<?php
require_once("security.php");
require_once("class/member_grup.php");

if (!isset($_GET['grup']) || !isset($_GET['user'])) {
    header("Location: index.php");
    exit;
}

$idgrup = $_GET['grup'];
$username = $_GET['user'];

$memberObj = new MemberGrup();

// Tambah hanya jika belum ada
if (!$memberObj->isMember($idgrup, $username)) {
    $memberObj->addMember($idgrup, $username);
}

header("Location: tambah_member_mahasiswa.php?id=$idgrup&status=added");
exit;
?>
