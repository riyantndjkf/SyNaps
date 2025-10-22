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
    header("Location: update_password.php?status=empty");
    exit;
}

$akun = new Akun();
$username = $_SESSION['username'];
$oldpwd = $_POST['oldpwd'];
$newpwd = $_POST['newpwd'];
$newpwd2 = $_POST['newpwd2'];

if ($newpwd !== $newpwd2) {
    header("Location: update_password.php?status=diff");
    exit;
}

$data = $akun->getAkun($username);
if (!$data || !password_verify($oldpwd, $data['password'])) {
    header("Location: update_password.php?status=wrong");
    exit;
}

$hasil = $akun->updatePassword($username, $newpwd);
if ($hasil > 0) {
    header("Location: update_password.php?status=success");
} else {
    header("Location: update_password.php?status=error");
}
exit;
?>
