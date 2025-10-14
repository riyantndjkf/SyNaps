<?php
require_once("class/akun.php");

$akun = new Akun();
$akun->register("admin", "admin123", null, null, 1);

echo "Admin berhasil dibuat!";
?>