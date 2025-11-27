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

// Cek apakah dosen ini adalah member grup (termasuk pembuat grup)
$memberObj = new MemberGrup();
$username_dosen = 'd' . $_SESSION['npk_dosen'];
$isPembuat = ($grup['username_pembuat'] == $_SESSION['username']);
$isMember = $memberObj->isMember($idgrup, $username_dosen);

// Cek apakah dosen ini adalah member grup (termasuk pembuat grup)
$username_dosen = $_SESSION['username'];
$isPembuat = ($grup['username_pembuat'] == $_SESSION['username']);
$isMember = $memberObj->isMember($idgrup, $username_dosen);
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

    echo '<form id="formEvent" enctype="multipart/form-data">

        <input type="hidden" name="idgrup" value="' . $idgrup . '">
        <div id="alert-msg" style="display:none; margin-bottom:15px; padding:10px; border-radius:4px; text-align:center; font-weight:bold;"></div>

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

        $btnSubmit.prop("disabled", true).css("background-color", "#ccc");

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
                    $btnSubmit.prop("disabled", false).css("background-color", "#007bff");
                }
            },
            error: function(){
                $alertMsg.removeClass("alert-success").addClass("alert-danger")
                    .text("Terjadi kesalahan saat mengirim data!")
                    .show();
                $btnSubmit.prop("disabled", false).css("background-color", "#007bff");
            }
        });
    });
});
</script>

<style>
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>

</body>
</html>