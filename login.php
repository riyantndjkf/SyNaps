<?php
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo "<p style='color:green; text-align:center;'>Anda telah berhasil logout.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Akun</title>
</head>
<body>
    <h2>Login</h2>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == "empty") echo "<p style='color:red;'>Semua field wajib diisi!</p>";
        elseif ($_GET['status'] == "error") echo "<p style='color:red;'>Username atau password salah!</p>";
    }
    ?>

<form method="post" action="proses_login.php">
    <p><label>Username</label> <input type="text" name="username" required></p>
    <p><label>Password</label> <input type="password" name="password" required></p>
    <p><button type="submit">Login</button></p>
</form>
</body>
</html>
