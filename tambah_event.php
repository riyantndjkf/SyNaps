<?php
require_once("security.php");
require_once("class/grup.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: display_grup.php");
    exit;
}

$idgrup = $_GET['id'];

$grupObj = new Grup();
$grup = $grupObj->getGrup($idgrup);

if (!$grup) {
    header("Location: display_grup.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Event Baru</title>
</head>
<body>

<h1>Tambah Event untuk Grup: <?= htmlentities($grup['nama']); ?></h1>

<form method="post" action="proses_tambah_event.php" enctype="multipart/form-data">

    <input type="hidden" name="idgrup" value="<?= $idgrup ?>">

    <p>
        <label>Judul Event</label><br>
        <input type="text" name="judul" required maxlength="45">
    </p>

    <p>
        <label>Tanggal Event</label><br>
        <input type="datetime-local" name="tanggal" required>
    </p>

    <p>
        <label>Jenis Event</label><br>
        <select name="jenis" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="Privat">Privat</option>
            <option value="Publik">Publik</option>
        </select>
    </p>

    <p>
        <label>Keterangan</label><br>
        <textarea name="keterangan" rows="4" cols="40"></textarea>
    </p>

    <p>
        <label>Poster Event (opsional)</label><br>
        <input type="file" name="poster" accept="image/jpeg, image/png">
    </p>

    <p>
        <button type="submit">Simpan Event</button>
        <a href="detail_grup.php?id=<?= $idgrup ?>">
            <button type="button">Kembali</button>
        </a>
    </p>

</form>

</body>
</html>
