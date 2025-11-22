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
</head>
<body>

<h1>Buat Grup Baru</h1>

<form method="post" action="proses_tambah_grup.php">
    <p>
        <label>Nama Grup</label><br>
        <input type="text" name="nama" required>
    </p>

    <p>
        <label>Deskripsi</label><br>
        <textarea name="deskripsi"></textarea>
    </p>

    <p>
        <label>Jenis Grup</label><br>
        <select name="jenis">
            <option value="Privat">Privat</option>
            <option value="Publik">Publik</option>
        </select>
    </p>

    <p>
        <button type="submit">Buat Grup</button>
        <a href="display_grup.php"><button type="button">Kembali</button></a>
    </p>
</form>

</body>
</html>
