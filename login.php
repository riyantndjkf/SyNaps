<?php
$logout_msg = "";
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $logout_msg = "<div class='alert alert-success'>Anda telah berhasil logout.</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akun</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="login-container">
        <h2>Log In</h2>
        <?php
        echo $logout_msg;
        if (isset($_GET['status'])) {
            if ($_GET['status'] == "empty") echo "<div class='alert alert-danger'>Semua field wajib diisi!</div>";
            elseif ($_GET['status'] == "error") echo "<div class='alert alert-danger'>Username atau password salah!</div>";
        }
        ?>

        <form method="post" action="proses_login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

</body>
</html>
