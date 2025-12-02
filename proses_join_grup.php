<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$kode = $_POST['kode_join'];
$username = $_SESSION['username'];

if (empty($kode)) {
    header("Location: display_grup.php?status=empty_code");
    exit;
}

$grupObj = new Grup();
$memberObj = new MemberGrup();

$grup = $grupObj->getGrupByCode($kode);

if (!$grup) {
    header("Location: display_grup.php?status=invalid_code");
    exit;
}

if ($memberObj->isMember($grup['idgrup'], $username)) {
    header("Location: display_grup.php?status=already_member");
    exit;
}

if ($memberObj->addMember($grup['idgrup'], $username)) {
    header("Location: display_grup.php?status=join_success");
} else {
    header("Location: display_grup.php?status=error");
}
?>