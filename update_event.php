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

$grupObj = new Grup();
$grup = $grupObj->getGrup($event['idgrup']);

$memberObj = new MemberGrup();
$username_dosen = $_SESSION['username'];
$isMember = $memberObj->isMember($event['idgrup'], $username_dosen);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Edit Event: <?= htmlentities($event['judul']); ?></h1>

    <div id="alert-msg" class="alert" style="display:none;"></div>

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
                <img src="images/event/<?= $event['judul_slug'] . '.' . $event['poster_extension']; ?>" class="preview">
            <?php else: ?>
                <i>Tidak ada poster</i>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Ganti Poster (opsional)</label>
            <input type="file" name="poster" id="poster" accept="image/jpeg, image/png">
        </div>

        <button type="submit" class="btn-save btn-update" id="btn-submit">Update Event</button>
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

        $btnSubmit.prop("disabled", true).addClass("btn-disabled");

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
                    $btnSubmit.prop("disabled", false).removeClass("btn-disabled");
                }
            },
            error: function(){
                $alertMsg.removeClass("alert-success").addClass("alert-danger")
                    .text("Terjadi kesalahan saat mengirim data!")
                    .show();
                $btnSubmit.prop("disabled", false).removeClass("btn-disabled");
            }
        });
    });
});
</script>
</body>
</html>