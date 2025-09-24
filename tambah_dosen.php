<?php
$mysqli = new mysqli("localhost", "root", "", "fullstack");

if ($mysqli->connect_error) {
    die("Koneksi Gagal: " . $mysqli->connect_error);
}
?>