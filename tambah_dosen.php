<?php
require_once("security.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dosen Baru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Tambah Dosen</h1>
        
        <form method="post" action="proses_tambah_dosen.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="npk">NPK</label>
                <input type="text" name="npk" id="npk" required maxlength="6" placeholder="Contoh: 192014">
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="foto">Foto Profil (Opsional)</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/jpg">
            </div>

            <button type="submit" class="btn-save">Simpan Data</button>
            <a href="display_dosen.php"><button type="button" class="btn-back">Kembali</button></a>
        </form> 
    </div>
    <script src="jquery-3.7.1.js"></script>
    <script>
    $("#npk").on("keyup blur", function(){
        var npk = $(this).val();
        $.post("ajax/check_duplicate.php", { id: npk, type: 'dosen' })
         .done(function(data){
             if(data == "exist") {
                 $("#npk").css("border", "2px solid red");
                 if($("#err-msg").length == 0) {
                     $("#npk").after("<span id='err-msg' style='color:red; font-size:12px;'>NPK sudah terdaftar!</span>");
                 }
                 $("button[type='submit']").prop("disabled", true);
                 $(".btn-save").addClass("btn-disabled");
             } else {
                 $("#npk").css("border", "1px solid #ccc");
                 $("#err-msg").remove();
                 $("button[type='submit']").prop("disabled", false);
                 $(".btn-save").removeClass("btn-disabled");
             }
         });
    });
    </script>
    <script src="theme.js"></script>
</body>
</html>