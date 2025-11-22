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

$memberObj->deleteMember($idgrup, $username);

header("Location: kelola_member.php?id=$idgrup&status=deleted");
exit;
?>
