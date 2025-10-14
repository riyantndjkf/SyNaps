<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login, arahkan ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Pengaman tambahan sederhana: IP & User-Agent
if (!isset($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}
if (!isset($_SESSION['agent'])) {
    $_SESSION['agent'] = $_SERVER['HTTP_USER_AGENT'];
}

// Jika data sesi tidak cocok, logout otomatis
if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
