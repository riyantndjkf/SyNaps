<?php
require_once("security.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dosen Baru</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        
        input[type="text"], input[type="password"], input[type="file"] { 
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }

        button { cursor: pointer; padding: 10px 20px; border: none; border-radius: 4px; font-size: 14px; margin-top: 10px; }
        .btn-save { background-color: #28a745; color: white; width: 100%; }
        .btn-save:hover { background-color: #218838; }
        .btn-back { background-color: #6c757d; color: white; width: 100%; margin-top: 5px; }
        .btn-back:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Dosen</h1>
        
        <form method="post" action="proses_tambah_dosen.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="npk">NPK</label>
                <input type="text" name="npk" id="npk" required maxlength="6" placeholder="Contoh: 192014">
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="foto">Foto Profil (Opsional)</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/jpg">
            </div>

            <button type="submit" class="btn-save">Simpan Data</button>
            <a href="display_dosen.php"><button type="button" class="btn-back">Kembali</button></a>
        </form> 
    </div>
</body>
</html>