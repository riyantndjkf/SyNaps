<?php
require_once("security.php");
require_once("class/grup.php");

// Cek apakah dosen
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

// Validasi: Hanya pembuat yang boleh edit
if (!$grup || $grup['username_pembuat'] != $_SESSION['username']) {
    header("Location: display_grup.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Grup</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        input[type="text"], textarea, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        textarea { resize: vertical; min-height: 100px; }
        button { cursor: pointer; padding: 10px 20px; border: none; border-radius: 4px; font-size: 14px; margin-top: 10px; }
        .btn-save { background-color: #28a745; color: white; width: 100%; }
        .btn-save:hover { background-color: #218838; }
        .btn-back { background-color: #6c757d; color: white; width: 100%; margin-top: 5px; }
        .btn-back:hover { background-color: #5a6268; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; font-size: 14px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <?php
    echo '<h1>Edit Grup: ' . htmlentities($grup['nama']) . '</h1>';

    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'empty') echo '<div class="alert alert-danger">Nama grup tidak boleh kosong!</div>';
        elseif ($_GET['status'] == 'error') echo '<div class="alert alert-danger">Terjadi kesalahan saat menyimpan!</div>';
    }

    // Logic untuk selected option
    $selPrivat = ($grup['jenis'] == 'Privat') ? 'selected' : '';
    $selPublik = ($grup['jenis'] == 'Publik') ? 'selected' : '';

    echo '<form method="post" action="proses_edit_grup.php">
        <input type="hidden" name="idgrup" value="' . $grup['idgrup'] . '">

        <div class="form-group">
            <label>Nama Grup</label>
            <input type="text" name="nama" value="' . htmlentities($grup['nama']) . '" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi">' . htmlentities($grup['deskripsi']) . '</textarea>
        </div>

        <div class="form-group">
            <label>Jenis Grup</label>
            <select name="jenis">
                <option value="Privat" ' . $selPrivat . '>Privat</option>
                <option value="Publik" ' . $selPublik . '>Publik</option>
            </select>
        </div>

        <button type="submit" class="btn-save">Simpan Perubahan</button>
        <a href="detail_grup.php?id=' . $idgrup . '"><button type="button" class="btn-back">Kembali</button></a>
    </form>';
    ?>
</div>

</body>
</html>