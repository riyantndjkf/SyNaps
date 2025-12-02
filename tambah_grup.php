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
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Buat Grup Baru</h1>

        <?php
        if (isset($_GET['status']) && $_GET['status'] == 'empty') {
            echo '<div class="alert alert-danger">Nama grup tidak boleh kosong!</div>';
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

            <button type="submit" class="btn-save">Buat Grup</button>
            <a href="display_grup.php"><button type="button" class="btn-back">Kembali</button></a>
        </form>
    </div>

</body>
</html>