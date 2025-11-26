<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

// Pastikan user login
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

// 1. Cek apakah kode valid
$grup = $grupObj->getGrupByCode($kode);

if (!$grup) {
    header("Location: display_grup.php?status=invalid_code");
    exit;
}

// 2. Cek apakah user sudah bergabung
if ($memberObj->isMember($grup['idgrup'], $username)) {
    header("Location: display_grup.php?status=already_member");
    exit;
}

// 3. Proses Join
if ($memberObj->addMember($grup['idgrup'], $username)) {
    header("Location: display_grup.php?status=join_success");
} else {
    header("Location: display_grup.php?status=error");
}
?>