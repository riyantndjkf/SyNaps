<?php
require_once("security.php");
require_once("class/akun.php");

$akun = new Akun();

// Ambil data dari form
$username  = isset($_POST['username']) ? $_POST['username'] : '';
$password  = isset($_POST['password']) ? $_POST['password'] : '';
$password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
$role      = isset($_POST['role']) ? $_POST['role'] : '';
$nrp       = isset($_POST['nrp']) ? $_POST['nrp'] : null;
$npk       = isset($_POST['npk']) ? $_POST['npk'] : null;
$nama      = isset($_POST['nama']) ? $_POST['nama'] : $username;

if ($username == '' || $password == '' || $password2 == '' || $role == '') {
    header("Location: registrasi.php?err=EMPTY");
    exit;
}

if ($password != $password2) {
    header("Location: registrasi.php?err=PWD");
    exit;
}

if ($akun->getAkun($username)) {
    header("Location: registrasi.php?err=EXIST");
    exit;
}

$isadmin = 0;
$nrp_final = ($role == 'mahasiswa') ? $nrp : null;
$npk_final = ($role == 'dosen') ? $npk : null;

$ok = $akun->register($username, $password, $nama, $nrp_final, $npk_final, $isadmin);

if ($ok) {
    header("Location: login.php?reg=success");
    exit;
} else {
    header("Location: registrasi.php?err=FAIL");
    exit;
}
?>