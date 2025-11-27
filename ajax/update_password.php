<?php
require_once("../security.php");
require_once("../class/akun.php");

if (!isset($_SESSION['username'])) {
    echo "error|unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "error|invalid_method";
    exit;
}

$oldpwd = $_POST['oldpwd'] ?? '';
$newpwd = $_POST['newpwd'] ?? '';
$newpwd2 = $_POST['newpwd2'] ?? '';

if (empty($oldpwd) || empty($newpwd) || empty($newpwd2)) {
    echo "error|empty_fields";
    exit;
}

if ($newpwd !== $newpwd2) {
    echo "error|password_mismatch";
    exit;
}

$akun = new Akun();
$username = $_SESSION['username'];
$data = $akun->getAkun($username);

if (!$data || !password_verify($oldpwd, $data['password'])) {
    echo "error|wrong_password";
    exit;
}

$hasil = $akun->updatePassword($username, $newpwd);
if ($hasil > 0) {
    echo "success";
} else {
    echo "error|db_failed";
}
?>
