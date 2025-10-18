<?php
require_once("security.php");
require_once("class/akun.php");
$akun = new Akun();

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Password</title>
</head>
<body>
    <h2>Update Password</h2>

    <?php
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == 'EMPTY') echo "<p style='color:red;'>Semua field wajib diisi!</p>";
        elseif ($_GET['msg'] == 'WRONG') echo "<p style='color:red;'>Password lama salah!</p>";
        elseif ($_GET['msg'] == 'DIFF') echo "<p style='color:red;'>Password baru dan konfirmasi tidak cocok!</p>";
        elseif ($_GET['msg'] == 'OK') echo "<p style='color:green;'>Password berhasil diubah!</p>";
        elseif ($_GET['msg'] == 'FAIL') echo "<p style='color:red;'>Terjadi kesalahan saat mengubah password!</p>";
    }
    ?>

    <form method="post" action="proses_update_password.php">
        <p><label>Password Lama</label><br>
        <input type="password" name="oldpwd" required></p>

        <p><label>Password Baru</label><br>
        <input type="password" name="newpwd" required></p>

        <p><label>Ulangi Password Baru</label><br>
        <input type="password" name="newpwd2" required></p>

        <p><button type="submit">Ubah Password</button></p>
    </form>

    <p><a href="index.php">Kembali ke Beranda</a></p>
</body>
</html>
