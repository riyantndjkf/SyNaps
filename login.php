<?php
// Cek logout di atas untuk pesan sukses (opsional, atau bisa digabung di bawah)
$logout_msg = "";
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $logout_msg = "<p style='color:green; text-align:center; font-weight:bold; background-color:#d4edda; padding:10px; border-radius:4px; border:1px solid #c3e6cb;'>Anda telah berhasil logout.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akun</title>
    <style>
        /* CSS Internal */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            width: 350px;
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .login-container h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.1);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #2c62a3;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #234e82;
        }
        
        /* Tambahan style untuk pesan error agar sedikit lebih rapi meski pakai tag <p> */
        p[style*="color:red"] {
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Log In</h2>
        <?php
        echo $logout_msg;
        if (isset($_GET['status'])) {
            if ($_GET['status'] == "empty") echo "<p style='color:red;'>Semua field wajib diisi!</p>";
            elseif ($_GET['status'] == "error") echo "<p style='color:red;'>Username atau password salah!</p>";
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