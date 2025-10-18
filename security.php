<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Cek login ---
if (!isset($_SESSION['username'])) {
    // Ambil URL lengkap dari halaman yang sedang dibuka
    $domain = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $url = "http://" . $domain . $uri;

    // Arahkan ke halaman login sambil membawa URL tujuan
    header("Location: login.php?url=" . urlencode($url));
    exit();
}

// --- Pengaman tambahan: cek IP dan User-Agent ---
if (!isset($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}
if (!isset($_SESSION['agent'])) {
    $_SESSION['agent'] = $_SERVER['HTTP_USER_AGENT'];
}

// Jika data sesi tidak cocok (kemungkinan sesi dicuri), logout otomatis
if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();

    // Simpan juga URL terakhir sebelum logout
    $domain = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $url = "http://" . $domain . $uri;

    header("Location: login.php?url=" . urlencode($url));
    exit();
}
?>
