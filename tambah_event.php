<?php
require_once("security.php");
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

$idgrup = $_GET['id'];

$grupObj = new Grup();
$grup = $grupObj->getGrup($idgrup);

if (!$grup) {
    header("Location: display_grup.php");
    exit;
}

$memberObj = new MemberGrup();
$username_dosen = $_SESSION['username'];
$isPembuat = ($grup['username_pembuat'] == $_SESSION['username']);
$isMember = $memberObj->isMember($idgrup, $username_dosen);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Event Baru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <?php
    echo '<h1>Tambah Event: ' . htmlentities($grup['nama']) . '</h1>';

    if (isset($_GET['status']) && $_GET['status'] == 'empty') {
        echo '<div class="alert alert-danger">Judul, Tanggal, dan Jenis harus diisi!</div>';
    }

    echo '<form id="formEvent" enctype="multipart/form-data">

        <input type="hidden" name="idgrup" value="' . $idgrup . '">
        <div id="alert-msg" style="display:none;" class="alert"></div>

        <div class="form-group">
            <label>Judul Event</label>
            <input type="text" name="judul" id="judul" required maxlength="45" placeholder="Contoh: Rapat Perdana">
        </div>

        <div class="form-group">
            <label>Tanggal Event</label>
            <input type="datetime-local" name="tanggal" id="tanggal" required>
        </div>

        <div class="form-group">
            <label>Jenis Event</label>
            <select name="jenis" id="jenis" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Privat">Privat</option>
                <option value="Publik">Publik</option>
            </select>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" id="keterangan" placeholder="Detail kegiatan..."></textarea>
        </div>

        <div class="form-group">
            <label>Poster Event (opsional)</label>
            <input type="file" name="poster" id="poster" accept="image/jpeg, image/png">
        </div>

        <button type="submit" class="btn-save" id="btn-submit">Simpan Event</button>
        <a href="detail_grup.php?id=' . $idgrup . '"><button type="button" class="btn-back">Kembali</button></a>

    </form>';
    ?>
</div>

<script src="jquery-3.7.1.js"></script>
<script>
$(document).ready(function(){
    $("#formEvent").on("submit", function(e){
        e.preventDefault();

        var formData = new FormData(this);
        var $alertMsg = $("#alert-msg");
        var $btnSubmit = $("#btn-submit");

        $btnSubmit.prop("disabled", true).addClass("btn-disabled");

        $.ajax({
            url: "ajax/tambah_event.php",
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
                        .text("Event berhasil ditambahkan!")
                        .show();
                    
                    setTimeout(function(){
                        window.location.href = "detail_grup.php?id=" + value + "&status=success";
                    }, 1500);
                } else {
                    var errorMsg = "Terjadi kesalahan!";
                    if(value === "validation_failed") errorMsg = "Judul, Tanggal, dan Jenis harus diisi!";
                    else if(value === "upload_failed") errorMsg = "Gagal upload poster!";
                    else if(value === "invalid_file_type") errorMsg = "Format file poster tidak didukung!";
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
<script src="theme.js"></script>
</body>
</html>