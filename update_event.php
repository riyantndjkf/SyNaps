<?php
require_once("security.php");
require_once("class/event.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

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

// Cek apakah dosen ini adalah member grup (termasuk pembuat grup)
$memberObj = new MemberGrup();
$username_dosen = 'd' . $_SESSION['npk_dosen'];
$isPembuat = ($grup['username_pembuat'] == $_SESSION['username']);
$isMember = $memberObj->isMember($event['idgrup'], $username_dosen);

// Gunakan username dosen yang sesungguhnya
$username_dosen = $_SESSION['username'];
$isMember = $memberObj->isMember($event['idgrup'], $username_dosen);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        input[type="text"], input[type="datetime-local"], textarea, select, input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        textarea { resize: vertical; min-height: 100px; }
        button { cursor: pointer; padding: 10px 20px; border: none; border-radius: 4px; font-size: 14px; margin-top: 10px; width: 100%; }
        .btn-save { background-color: #ffc107; color: black; font-weight: bold; }
        .btn-save:hover { background-color: #e0a800; }
        .btn-back { background-color: #6c757d; color: white; margin-top: 5px; }
        .btn-back:hover { background-color: #5a6268; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; font-size: 14px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .poster-preview { max-width: 200px; border-radius: 4px; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Event: <?= htmlentities($event['judul']); ?></h1>

    <div id="alert-msg" style="display:none;"></div>

    <form id="formUpdateEvent" enctype="multipart/form-data">
        <input type="hidden" name="idevent" value="<?= $event['idevent'] ?>">
        <input type="hidden" name="idgrup" value="<?= $event['idgrup'] ?>">

        <div class="form-group">
            <label>Judul Event</label>
            <input type="text" name="judul" id="judul" value="<?= htmlentities($event['judul']); ?>" required>
        </div>

        <div class="form-group">
            <label>Tanggal Event</label>
            <input type="datetime-local" 
                   name="tanggal" 
                   id="tanggal"
                   value="<?= date('Y-m-d\TH:i', strtotime($event['tanggal'])); ?>" 
                   required>
        </div>

        <div class="form-group">
            <label>Jenis Event</label>
            <select name="jenis" id="jenis" required>
                <option value="Privat" <?= ($event['jenis']=="Privat") ? "selected" : "" ?>>Privat</option>
                <option value="Publik" <?= ($event['jenis']=="Publik") ? "selected" : "" ?>>Publik</option>
            </select>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" id="keterangan"><?= htmlentities($event['keterangan']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Poster Saat Ini</label>
            <?php if (!empty($event['poster_extension'])): ?>
                <img src="images/event/<?= $event['judul_slug'] . '.' . $event['poster_extension']; ?>" class="poster-preview">
            <?php else: ?>
                <i>Tidak ada poster</i>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Ganti Poster (opsional)</label>
            <input type="file" name="poster" id="poster" accept="image/jpeg, image/png">
        </div>

        <button type="submit" class="btn-save" id="btn-submit">Update Event</button>
        <a href="detail_grup.php?id=<?= $event['idgrup']; ?>">
            <button type="button" class="btn-back">Kembali</button>
        </a>
    </form>
</div>

<script src="jquery-3.7.1.js"></script>
<script>
$(document).ready(function(){
    $("#formUpdateEvent").on("submit", function(e){
        e.preventDefault();

        var formData = new FormData(this);
        var $alertMsg = $("#alert-msg");
        var $btnSubmit = $("#btn-submit");

        $btnSubmit.prop("disabled", true).css("background-color", "#ccc");

        $.ajax({
            url: "ajax/update_event.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
                var response = data.trim();
                var parts = response.split('|');
                var status = parts[0];
                var value = parts[1] || '';

                if(status === "success"){
                    $alertMsg.removeClass("alert-danger").addClass("alert-success")
                        .text("Event berhasil diupdate!")
                        .show();
                    
                    setTimeout(function(){
                        window.location.href = "detail_grup.php?id=" + value + "&status=success";
                    }, 1500);
                } else {
                    var errorMsg = "Terjadi kesalahan!";
                    if(value === "validation_failed") errorMsg = "Judul, Tanggal, dan Jenis harus diisi!";
                    else if(value === "upload_failed") errorMsg = "Gagal upload poster!";
                    else if(value === "invalid_file_type") errorMsg = "Format file poster tidak didukung!";
                    else if(value === "event_not_found") errorMsg = "Event tidak ditemukan!";
                    else if(value === "unauthorized") errorMsg = "Anda tidak memiliki akses!";
                    
                    $alertMsg.removeClass("alert-success").addClass("alert-danger")
                        .text(errorMsg)
                        .show();
                    $btnSubmit.prop("disabled", false).css("background-color", "#ffc107");
                }
            },
            error: function(){
                $alertMsg.removeClass("alert-success").addClass("alert-danger")
                    .text("Terjadi kesalahan saat mengirim data!")
                    .show();
                $btnSubmit.prop("disabled", false).css("background-color", "#ffc107");
            }
        });
    });
});
</script>

</body>
</html>
