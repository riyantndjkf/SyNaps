<?php
require_once("security.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synaps - Home</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Synaps Panel</h1>
        </div>
        <div class="main-content">
            <h2>Selamat Datang,
                <?php 
                    if (!empty($_SESSION['nrp_mahasiswa'])) {
                        echo "Mahasiswa " . $_SESSION['username'] . "</h2>";
                        echo "<p>Gunakan menu di dibawah untuk mengganti password Anda atau logout dari sistem.</p>";
                    } elseif (!empty($_SESSION['npk_dosen'])) {
                        echo "Dosen " . $_SESSION['username'] . "</h2>";
                        echo "<p>Gunakan menu di dibawah untuk mengganti password Anda atau logout dari sistem.</p>";
                    } else {
                        echo "Admin</h2> ";
                        echo "<p>Silakan pilih menu di bawah untuk mengelola data dosen atau mahasiswa.</p>";
                    }
                ?>
            
            <div class="menu">
                <h3>Menu</h3>

                <?php if ($_SESSION['isadmin'] == 1) { ?>
                    <a href="display_dosen.php">Kelola Dosen</a><br>
                    <a href="display_mahasiswa.php">Kelola Mahasiswa</a><br>
                    <a href="update_password.php">Change Password</a><br>
                    <a href="logout.php">Logout</a><br>

                <?php } else { ?>
                    <a href="update_password.php">Update Password</a><br>
                    <a href="logout.php">Logout</a><br>
                <?php } ?>
            </div>

            <div class="content">
                
            </div>
        </div>
    </div>
</body>
</html>
