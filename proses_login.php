<?php
require_once("security.php");
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

        // Redirect ke halaman awal atau halaman yang diminta
        $url = isset($_POST['url']) ? $_POST['url'] : 'index.php';
        header("Location: " . $url);

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
