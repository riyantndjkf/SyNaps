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
    <link rel="stylesheet" href="css/style.css">
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

        <button type="submit" name="update" class="btn-save btn-update">Update Data</button>
        <a href="display_dosen.php"><button type="button" class="btn-back">Kembali</button></a>
    </form> 
</div>
<script src="js/theme.js"></script>
</body>
</html>
