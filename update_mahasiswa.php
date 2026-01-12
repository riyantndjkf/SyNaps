<?php
require_once("security.php");
require_once("class/mahasiswa.php");
$mhsObj = new Mahasiswa();
$mahasiswa = $mhsObj->getMahasiswa($_GET['nrp']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Edit Mahasiswa: <?php echo htmlentities($mahasiswa['nama']); ?></h1>

    <form method="post" action="proses_update_mahasiswa.php" enctype="multipart/form-data">
        <input type="hidden" name="nrp" value="<?php echo $mahasiswa['nrp']; ?>">

        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?php echo htmlentities($mahasiswa['nama']); ?>" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" required>
                <option value="Pria" <?php if ($mahasiswa['gender']=='Pria') echo 'selected'; ?>>Pria</option>
                <option value="Wanita" <?php if ($mahasiswa['gender']=='Wanita') echo 'selected'; ?>>Wanita</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo $mahasiswa['tanggal_lahir']; ?>" required>
        </div>

        <div class="form-group">
            <label for="angkatan">Angkatan</label>
            <input type="number" name="angkatan" id="angkatan" value="<?php echo $mahasiswa['angkatan']; ?>" required>
        </div>

        <div class="form-group">
            <label>Foto Saat Ini</label>
            <?php
            if (!empty($mahasiswa['foto_extention'])) {
                $path = "images/" . $mahasiswa['nrp'] . "." . $mahasiswa['foto_extention'];
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
            <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/gif">
        </div>

        <button type="submit" name="update" class="btn-save btn-update">Update Data</button>
        <a href="display_mahasiswa.php"><button type="button" class="btn-back">Kembali</button></a>
    </form>
</div>
<script src="js/theme.js"></script>
</body>
</html>
