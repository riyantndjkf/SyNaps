<?php
require_once("security.php");
require_once("class/dosen.php");

$dosenObj = new Dosen();
$dosen = $dosenObj->getDosen($_GET['npk']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dosen</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        
        input[type="text"], input[type="file"] { 
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        
        img.preview { width: 120px; border-radius: 4px; border: 1px solid #ddd; padding: 2px; margin-top: 5px;}

        button { cursor: pointer; padding: 10px 20px; border: none; border-radius: 4px; font-size: 14px; margin-top: 10px; }
        .btn-save { background-color: #ffc107; color: black; width: 100%; font-weight: bold; }
        .btn-save:hover { background-color: #e0a800; }
        .btn-back { background-color: #6c757d; color: white; width: 100%; margin-top: 5px; }
        .btn-back:hover { background-color: #5a6268; }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Dosen: <?php echo htmlentities($dosen['nama']); ?></h1>

    <form method="post" action="proses_update_dosen.php" enctype="multipart/form-data">
        <input type="hidden" name="npk" value="<?php echo $dosen['npk']; ?>">

        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?php echo htmlentities($dosen['nama']); ?>" required>
        </div>

        <div class="form-group">
            <label>Foto Saat Ini</label>
            <?php
            if (!empty($dosen['foto_extension'])) {
                $path = "images/" . $dosen['npk'] . "." . $dosen['foto_extension'];
                if (file_exists($path)) {
                    echo "<img src='$path' class='preview'>";
                } else {
                    echo "File foto tidak ditemukan.";
                }
            } else {
                echo "<i>Belum ada foto.</i>";
            }
            ?>
        </div>

        <div class="form-group">
            <label for="foto">Ganti Foto (opsional)</label>
            <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/jpg">
        </div>

        <button type="submit" name="update" class="btn-save">Update Data</button>
        <a href="display_dosen.php"><button type="button" class="btn-back">Kembali</button></a>
    </form> 
</div>
</body>
</html>