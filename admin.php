<?php
require_once("class/akun.php");

// Buat koneksi manual (menyesuaikan dengan konfigurasi classParent)
$mysqli = new mysqli("localhost", "root", "", "fullstack"); // ⚠️ ganti nama DB sesuai punya kamu

if ($mysqli->connect_errno) {
    die("Gagal konek DB: " . $mysqli->connect_error);
}


// --- Cek apakah user sudah ada ---
$username = "218122";
$password = "218122";

$stmt = $mysqli->prepare("SELECT * FROM akun WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("⚠️ Admin sudah ada. Hapus file init_admin.php untuk keamanan.");
}

// --- Hash password dan buat akun ---
$hash = password_hash($password, PASSWORD_DEFAULT);
$isadmin = 0;

// $stmt = $mysqli->prepare("INSERT INTO akun (username, password,npk_dosen, isadmin) VALUES (?, ?, ?,?)");
$stmt = $mysqli->prepare("INSERT INTO akun (username, password,nrp_mahasiswa, isadmin) VALUES (?, ?, ?,?)");

$stmt->bind_param("sssi", $username, $hash,$username, $isadmin);

if ($stmt->execute()) {
    echo "✅ Akun berhasil dibuat!<br>";
    echo "Username: <b>$username</b><br>Password: <b>$password</b><br>";
    echo "<br>Silakan login dan ubah password Anda dari menu Update Password.";
} else {
    echo "❌ Gagal membuat user: " . $stmt->error;
}
?>
