<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

$idgrup = $_GET['id'];
$username = $_SESSION['username'];

$g = (new Grup())->getGrup($idgrup);

if ($g['username_pembuat'] == $username) {
    header("Location: display_grup.php?status=creator_no_exit");
    exit;
}

$mg = new MemberGrup();
$mg->deleteMember($idgrup, $username);

header("Location: display_grup.php?status=keluar_success");
exit;
?>
