<?php
require_once("security.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synaps - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Synaps Panel</h1>
        </div>
        <div class="main-content">
            <div class="menu">
                <h3>Menu</h3>

                <?php if ($_SESSION['isadmin'] == 1) { ?>
                    <!-- Menu untuk Admin -->
                    <a href="display_dosen.php">Kelola Dosen</a><br>
                    <a href="display_mahasiswa.php">Kelola Mahasiswa</a><br>
                    <a href="change_password.php">Change Password</a><br>
                    <a href="logout.php">Logout</a><br>

                <?php } else { ?>
                    <!-- Menu untuk Dosen / Mahasiswa -->
                    <a href="update_password.php">Update Password</a><br>
                    <a href="logout.php">Logout</a><br>
                <?php } ?>
            </div>

            <div class="content">
                <h2>Selamat Datang, 
                    <?php 
                        // Tampilkan nama user berdasarkan login
                        if (!empty($_SESSION['nrp_mahasiswa'])) {
                            echo "Mahasiswa " . htmlspecialchars($_SESSION['username']);
                        } elseif (!empty($_SESSION['npk_dosen'])) {
                            echo "Dosen " . htmlspecialchars($_SESSION['username']);
                        } else {
                            echo "Admin ";
                        }
                    ?>!
                </h2>

                <?php if ($_SESSION['isadmin'] == 1) { ?>
                    <p>Silakan pilih menu di samping untuk mengelola data dosen atau mahasiswa.</p>
                <?php } else { ?>
                    <p>Gunakan menu di samping untuk mengganti password Anda atau logout dari sistem.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
