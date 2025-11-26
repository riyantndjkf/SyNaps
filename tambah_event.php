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
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        input[type="text"], input[type="datetime-local"], textarea, select, input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        textarea { resize: vertical; min-height: 100px; }
        button { cursor: pointer; padding: 10px 20px; border: none; border-radius: 4px; font-size: 14px; margin-top: 10px; width: 100%; }
        .btn-save { background-color: #007bff; color: white; margin-bottom: 10px;}
        .btn-save:hover { background-color: #0056b3; }
        .btn-back { background-color: #6c757d; color: white; }
        .btn-back:hover { background-color: #5a6268; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; font-size: 14px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <?php
    echo '<h1>Tambah Event: ' . htmlentities($grup['nama']) . '</h1>';

    if (isset($_GET['status']) && $_GET['status'] == 'empty') {
        echo '<div class="alert alert-danger">Judul, Tanggal, dan Jenis harus diisi!</div>';
    }

    echo '<form method="post" action="proses_tambah_event.php" enctype="multipart/form-data">

        <input type="hidden" name="idgrup" value="' . $idgrup . '">

        <div class="form-group">
            <label>Judul Event</label>
            <input type="text" name="judul" required maxlength="45" placeholder="Contoh: Rapat Perdana">
        </div>

        <div class="form-group">
            <label>Tanggal Event</label>
            <input type="datetime-local" name="tanggal" required>
        </div>

        <div class="form-group">
            <label>Jenis Event</label>
            <select name="jenis" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Privat">Privat</option>
                <option value="Publik">Publik</option>
            </select>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" placeholder="Detail kegiatan..."></textarea>
        </div>

        <div class="form-group">
            <label>Poster Event (opsional)</label>
            <input type="file" name="poster" accept="image/jpeg, image/png">
        </div>

        <button type="submit" class="btn-save">Simpan Event</button>
        <a href="detail_grup.php?id=' . $idgrup . '"><button type="button" class="btn-back">Kembali</button></a>

    </form>';
    ?>
</div>

</body>
</html>