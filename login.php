<?php
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo "<p style='color:green; text-align:center;'>Anda telah berhasil logout.</p>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Akun</title>
</head>
<body>
    <h2>Login</h2>

    <?php
    if (isset($_GET['err'])) {
        if ($_GET['err'] == "empty") echo "<p style='color:red;'>Harap isi semua field!</p>";
        elseif ($_GET['err'] == "invalid") echo "<p style='color:red;'>Username atau password salah!</p>";
    }

    if (isset($_GET['reg']) && $_GET['reg'] == 'success') {
        echo "<p style='color:green;'>Registrasi berhasil! Silakan login.</p>";
    }

    $url = isset($_GET['url']) ? $_GET['url'] : 'index.php';
    ?>

<form method="post" action="proses_login.php">
    <input type="hidden" name="url" value="<?= htmlspecialchars($url) ?>">
    <p><label>Username</label> <input type="text" name="username" required></p>
    <p><label>Password</label> <input type="password" name="password" required></p>
    <p><button type="submit">Login</button></p>
</form>


    <p>Belum punya akun? <a href="registrasi.php">Daftar di sini</a></p>
</body>
</html>
