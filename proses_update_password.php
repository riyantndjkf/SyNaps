<?php
require_once("security.php");
require_once("class/akun.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (
    empty($_POST['oldpwd']) ||
    empty($_POST['newpwd']) ||
    empty($_POST['newpwd2'])
) {
    header("Location: update_password.php?msg=EMPTY");
    exit;
}

$akun = new Akun();
$username = $_SESSION['username'];
$oldpwd = $_POST['oldpwd'];
$newpwd = $_POST['newpwd'];
$newpwd2 = $_POST['newpwd2'];

// Cek password baru dan konfirmasi
if ($newpwd !== $newpwd2) {
    header("Location: update_password.php?msg=DIFF");
    exit;
}

// Ambil data akun
$data = $akun->getAkun($username);
if (!$data || !password_verify($oldpwd, $data['password'])) {
    header("Location: update_password.php?msg=WRONG");
    exit;
}

// Update password
$hasil = $akun->updatePassword($username, $newpwd);
if ($hasil > 0) {
    header("Location: update_password.php?msg=OK");
} else {
    header("Location: update_password.php?msg=FAIL");
}
exit;
?>
