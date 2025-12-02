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
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <?php
    echo '<h1>Edit Grup: ' . htmlentities($grup['nama']) . '</h1>';

    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'empty') echo '<div class="alert alert-danger">Nama grup tidak boleh kosong!</div>';
        elseif ($_GET['status'] == 'error') echo '<div class="alert alert-danger">Terjadi kesalahan saat menyimpan!</div>';
    }

    $selPrivat = ($grup['jenis'] == 'Privat') ? 'selected' : '';
    $selPublik = ($grup['jenis'] == 'Publik') ? 'selected' : '';

    echo '<form id="formEditGrup" method="post">
        <input type="hidden" name="idgrup" value="' . $grup['idgrup'] . '">
        
        <div id="alert-msg" class="alert" style="display:none;"></div>

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

        <button type="submit" class="btn-save btn-update" id="btn-submit">Simpan Perubahan</button>
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

        $btnSubmit.prop("disabled", true).addClass("btn-disabled");

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