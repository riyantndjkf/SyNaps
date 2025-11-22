<?php
require_once("security.php");
require_once("class/event.php");
require_once("class/grup.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: display_grup.php");
    exit;
}

$idevent = $_GET['id'];

$eventObj = new Event();
$event = $eventObj->getEvent($idevent);

if (!$event) {
    header("Location: display_grup.php");
    exit;
}

// Ambil informasi grup untuk kembali ke detail grup
$grupObj = new Grup();
$grup = $grupObj->getGrup($event['idgrup']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
</head>
<body>

<h1>Edit Event: <?= htmlentities($event['judul']); ?></h1>

<form method="post" action="proses_update_event.php" enctype="multipart/form-data">

    <input type="hidden" name="idevent" value="<?= $event['idevent'] ?>">
    <input type="hidden" name="idgrup" value="<?= $event['idgrup'] ?>">

    <p>
        <label>Judul Event</label><br>
        <input type="text" name="judul" value="<?= htmlentities($event['judul']); ?>" required>
    </p>

    <p>
        <label>Tanggal Event</label><br>
        <input type="datetime-local" 
               name="tanggal" 
               value="<?= date('Y-m-d\TH:i', strtotime($event['tanggal'])); ?>" 
               required>
    </p>

    <p>
        <label>Jenis Event</label><br>
        <select name="jenis" required>
            <option value="Kegiatan"   <?= ($event['jenis']=="Kegiatan") ? "selected" : "" ?>>Kegiatan</option>
            <option value="Pengumuman" <?= ($event['jenis']=="Pengumuman") ? "selected" : "" ?>>Pengumuman</option>
            <option value="Tugas"      <?= ($event['jenis']=="Tugas") ? "selected" : "" ?>>Tugas</option>
        </select>
    </p>

    <p>
        <label>Keterangan</label><br>
        <textarea name="keterangan" rows="4" cols="40"><?= htmlentities($event['keterangan']); ?></textarea>
    </p>

    <p>
        <label>Poster Saat Ini</label><br>
        <?php if (!empty($event['poster_extension'])): ?>
            <img src="images/event/<?= $event['judul_slug'] . '.' . $event['poster_extension']; ?>" width="140">
        <?php else: ?>
            <i>Tidak ada poster</i>
        <?php endif; ?>
    </p>

    <p>
        <label>Ganti Poster (opsional)</label><br>
        <input type="file" name="poster" accept="image/jpeg, image/png">
    </p>

    <p>
        <button type="submit">Update Event</button>
        <a href="detail_grup.php?id=<?= $event['idgrup']; ?>">
            <button type="button">Kembali</button>
        </a>
    </p>

</form>

</body>
</html>
