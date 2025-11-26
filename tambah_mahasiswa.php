<?php
require_once("security.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa Baru</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        
        input[type="text"], input[type="password"], input[type="date"], input[type="number"], input[type="file"] { 
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        
        .radio-group label { font-weight: normal; display: inline; margin-right: 15px; }
        .radio-group input { width: auto; margin-right: 5px; }

        button { cursor: pointer; padding: 10px 20px; border: none; border-radius: 4px; font-size: 14px; margin-top: 10px; }
        .btn-save { background-color: #28a745; color: white; width: 100%; }
        .btn-save:hover { background-color: #218838; }
        .btn-back { background-color: #6c757d; color: white; width: 100%; margin-top: 5px; }
        .btn-back:hover { background-color: #5a6268; }

        #err-msg { display: block; margin-top: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Mahasiswa</h1>
        
        <form method="post" action="proses_tambah_mahasiswa.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nrp">NRP</label>
                <input type="text" name="nrp" id="nrp" required maxlength="9" placeholder="Contoh: 160423001">
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

            <div class="form-group radio-group">
                <label>Gender</label><br>
                <input type="radio" name="gender" value="Pria" id="pria" required> <label for="pria">Pria</label>
                <input type="radio" name="gender" value="Wanita" id="wanita"> <label for="wanita">Wanita</label>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" required>
            </div>

            <div class="form-group">
                <label for="angkatan">Angkatan</label>
                <input type="number" name="angkatan" id="angkatan" required min="2000" max="2100" placeholder="Contoh: 2023">
            </div>

            <div class="form-group">
                <label for="foto">Foto Profil (Opsional)</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/gif">
            </div>

            <button type="submit" class="btn-save">Simpan Data</button>
            <a href="display_mahasiswa.php"><button type="button" class="btn-back">Kembali</button></a>
        </form> 
    </div>

    <script src="jquery-3.7.1.js"></script>
    <script>
    $("#nrp").on("keyup blur", function(){
        var nrp = $(this).val();
        $.post("ajax/check_duplicate.php", { id: nrp, type: 'mahasiswa' })
         .done(function(data){
             if(data == "exist") {
                 $("#nrp").css("border", "2px solid red");
                 if($("#err-msg").length == 0) {
                     $("#nrp").after("<span id='err-msg' style='color:red; font-size:12px;'>NRP sudah terdaftar!</span>");
                 }
                 $("button[type='submit']").prop("disabled", true);
                 $(".btn-save").css("background-color", "#ccc");
             } else {
                 $("#nrp").css("border", "1px solid #ccc");
                 $("#err-msg").remove();
                 $("button[type='submit']").prop("disabled", false);
                 $(".btn-save").css("background-color", "#28a745");
             }
         });
    });
    </script>
</body>
</html>