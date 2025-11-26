<?php
require_once("security.php");
require_once("class/akun.php");

// Pastikan user login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ganti Password</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f4f4; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container { 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            width: 100%;
            max-width: 400px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        h2 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #555; }
        input[type="password"] { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box;
        }
        
        button { 
            width: 100%; 
            padding: 10px; 
            background-color: #28a745; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            font-size: 16px; 
            cursor: pointer; 
            margin-top: 10px;
        }
        button:hover { background-color: #218838; }

        .btn-back {
            background-color: #6c757d;
            margin-top: 10px;
        }
        .btn-back:hover { background-color: #5a6268; }

        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-size: 14px;}
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Ganti Password</h2>

        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<div class="alert alert-success">Password berhasil diubah!</div>';
            } else {
                echo '<div class="alert alert-danger">';
                if ($_GET['status'] == 'empty') echo "Semua field wajib diisi!";
                elseif ($_GET['status'] == 'wrong') echo "Password lama salah!";
                elseif ($_GET['status'] == 'diff') echo "Konfirmasi password tidak cocok!";
                elseif ($_GET['status'] == 'error') echo "Terjadi kesalahan sistem!";
                echo '</div>';
            }
        }
        ?>

        <form method="post" action="proses_update_password.php">
            <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="oldpwd" required>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="newpwd" required>
            </div>

            <div class="form-group">
                <label>Ulangi Password Baru</label>
                <input type="password" name="newpwd2" required>
            </div>

            <button type="submit">Simpan Password</button>
            <a href="index.php"><button type="button" class="btn-back">Kembali ke Home</button></a>
        </form>
    </div>

</body>
</html>