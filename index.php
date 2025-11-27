<?php
require_once("security.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synaps - Home</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SyNaps Panel</h1>
        </div>
        <div class="main-content">
            <h2>Selamat Datang, 
                <?php 
                if ($_SESSION['isadmin'] == 1) {
                    echo "Admin " . htmlentities($_SESSION['username']);
                } elseif (!empty($_SESSION['npk_dosen'])) {
                    echo "Dosen " . htmlentities($_SESSION['username']);
                } elseif (!empty($_SESSION['nrp_mahasiswa'])) {
                    echo "Mahasiswa " . htmlentities($_SESSION['username']);
                }
                ?>
            </h2>
            
            <p>Gunakan menu di bawah untuk akses fitur.</p>
            
            <div class="menu">
                <h3>Menu Utama</h3>

                <?php if ($_SESSION['isadmin'] == 1): ?>
                    <a href="display_dosen.php">Kelola Dosen</a><br>
                    <a href="display_mahasiswa.php">Kelola Mahasiswa</a><br>

                <?php elseif (!empty($_SESSION['npk_dosen'])): ?>
                    <a href="display_grup.php">Kelola Grup Saya</a><br>
                    <a href="tambah_grup.php">Buat Grup Baru</a><br>

                <?php elseif (!empty($_SESSION['nrp_mahasiswa'])): ?>
                    <a href="display_grup.php">Lihat & Gabung Grup</a><br>

                <?php endif; ?>
                <a href="update_password.php">Ganti Password</a><br>
                <hr>
                <a href="logout.php" style="color: red;">Logout</a><br>
            </div>

        </div>
    </div>
</body>
</html>