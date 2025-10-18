<?php
session_start();
require_once("class/akun.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan password wajib diisi!'); window.location='login.php';</script>";
        exit;
    }

    $akun = new Akun();
    $row = $akun->login($username, $password);

    if ($row !== false) {
        // Simpan data ke session
        $_SESSION['username'] = $row['username'];
        $_SESSION['isadmin'] = $row['isadmin'];
        $_SESSION['nrp_mahasiswa'] = $row['nrp_mahasiswa'];
        $_SESSION['npk_dosen'] = $row['npk_dosen'];

        // Cek apakah ada halaman terakhir tersimpan
        if (isset($_SESSION['last_page'])) {
            $redirect_url = $_SESSION['last_page'];
            unset($_SESSION['last_page']); // hapus biar tidak nyangkut
        } else {
            $redirect_url = 'index.php';
        }

        header("Location: " . $redirect_url);
        exit;
    } else {
        echo "<script>alert('Username atau password salah!'); window.location='login.php';</script>";
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
