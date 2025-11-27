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

    echo '<form id="formEditGrup" method="post">
        <input type="hidden" name="idgrup" value="' . $grup['idgrup'] . '">
        
        <div id="alert-msg" style="display:none; margin-bottom:15px; padding:10px; border-radius:4px; text-align:center; font-weight:bold;"></div>

        <div class="form-group">
            <label>Nama Grup</label>
            <input type="text" name="nama" id="nama" value="' . htmlentities($grup['nama']) . '" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi">' . htmlentities($grup['deskripsi']) . '</textarea>
        </div>

        <div class="form-group">
            <label>Jenis Grup</label>
            <select name="jenis" id="jenis">
                <option value="Privat" ' . $selPrivat . '>Privat</option>
                <option value="Publik" ' . $selPublik . '>Publik</option>
            </select>
        </div>

        <button type="submit" class="btn-save" id="btn-submit">Simpan Perubahan</button>
        <a href="detail_grup.php?id=' . $idgrup . '"><button type="button" class="btn-back">Kembali</button></a>
    </form>';
    ?>
</div>

<script src="jquery-3.7.1.js"></script>
<script>
$(document).ready(function(){
    $("#formEditGrup").on("submit", function(e){
        e.preventDefault();

        var idgrup = $("input[name='idgrup']").val();
        var nama = $("input[name='nama']").val();
        var deskripsi = $("textarea[name='deskripsi']").val();
        var jenis = $("select[name='jenis']").val();
        var $alertMsg = $("#alert-msg");
        var $btnSubmit = $("#btn-submit");

        $btnSubmit.prop("disabled", true).css("background-color", "#ccc");

        $.ajax({
            url: "ajax/edit_grup.php",
            type: "POST",
            data: { idgrup: idgrup, nama: nama, deskripsi: deskripsi, jenis: jenis },
            success: function(data){
                var response = data.trim();
                var parts = response.split('|');
                var status = parts[0];
                var value = parts[1] || '';

                if(status === "success"){
                    $alertMsg.removeClass("alert-danger").addClass("alert-success")
                        .text("Grup berhasil diupdate!")
                        .show();
                    
                    setTimeout(function(){
                        window.location.href = "detail_grup.php?id=" + value + "&status=update_success";
                    }, 1500);
                } else {
                    var errorMsg = "Terjadi kesalahan!";
                    if(value === "nama_required") errorMsg = "Nama grup tidak boleh kosong!";
                    else if(value === "unauthorized_access") errorMsg = "Anda tidak memiliki akses untuk mengedit grup ini!";
                    
                    $alertMsg.removeClass("alert-success").addClass("alert-danger")
                        .text(errorMsg)
                        .show();
                    $btnSubmit.prop("disabled", false).css("background-color", "#28a745");
                }
            },
            error: function(){
                $alertMsg.removeClass("alert-success").addClass("alert-danger")
                    .text("Terjadi kesalahan saat mengirim data!")
                    .show();
                $btnSubmit.prop("disabled", false).css("background-color", "#28a745");
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