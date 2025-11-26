<?php
require_once("security.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Buat Grup Baru</title>
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
            max-width: 500px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #555; }
        
        input[type="text"], textarea, select { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box;
            font-family: inherit;
        }
        
        textarea { resize: vertical; min-height: 80px; }

        button { 
            width: 100%; 
            padding: 10px; 
            background-color: #007bff; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            font-size: 16px; 
            cursor: pointer; 
            margin-top: 10px;
        }
        button:hover { background-color: #0056b3; }

        .btn-back {
            background-color: #6c757d;
            margin-top: 10px;
        }
        .btn-back:hover { background-color: #5a6268; }

        .alert-danger { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
            padding: 10px; 
            margin-bottom: 15px; 
            border-radius: 4px; 
            text-align: center; 
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Buat Grup Baru</h1>

        <?php
        if (isset($_GET['status']) && $_GET['status'] == 'empty') {
            echo '<div class="alert-danger">Nama grup tidak boleh kosong!</div>';
        }
        ?>

        <form method="post" action="proses_tambah_grup.php">
            <div class="form-group">
                <label>Nama Grup</label>
                <input type="text" name="nama" required placeholder="Contoh: Panitia ILPC 2024">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" placeholder="Jelaskan tujuan grup ini..."></textarea>
            </div>

            <div class="form-group">
                <label>Jenis Grup</label>
                <select name="jenis">
                    <option value="Privat">Privat (Hanya bisa join lewat kode)</option>
                    <option value="Publik">Publik (Muncul di daftar pencarian)</option>
                </select>
            </div>

            <button type="submit">Buat Grup</button>
            <a href="display_grup.php"><button type="button" class="btn-back">Kembali</button></a>
        </form>
    </div>

</body>
</html>