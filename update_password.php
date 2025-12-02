<?php
require_once("security.php");
require_once("class/akun.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ganti Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Ganti Password</h2>

        <div id="alert-msg" class="alert" style="display:none;"></div>

        <form id="formUpdatePassword" method="post">
            <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="oldpwd" id="oldpwd" required>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="newpwd" id="newpwd" required>
            </div>

            <div class="form-group">
                <label>Ulangi Password Baru</label>
                <input type="password" name="newpwd2" id="newpwd2" required>
            </div>

            <button type="submit" id="btn-submit" class="btn-save btn-update">Simpan Password</button>
            <a href="index.php"><button type="button" class="btn-back">Kembali ke Home</button></a>
        </form>
    </div>

    <script src="jquery-3.7.1.js"></script>
    <script>
    $(document).ready(function(){
        $("#formUpdatePassword").on("submit", function(e){
            e.preventDefault();

            var oldpwd = $("#oldpwd").val();
            var newpwd = $("#newpwd").val();
            var newpwd2 = $("#newpwd2").val();
            var $alertMsg = $("#alert-msg");
            var $btnSubmit = $("#btn-submit");

            $btnSubmit.prop("disabled", true).addClass("btn-disabled");

            $.ajax({
                url: "ajax/update_password.php",
                type: "POST",
                data: { oldpwd: oldpwd, newpwd: newpwd, newpwd2: newpwd2 },
                success: function(data){
                    var response = data.trim();
                    var parts = response.split('|');
                    var status = parts[0];
                    var reason = parts[1] || '';

                    if(status === "success"){
                        $alertMsg.removeClass("alert-danger").addClass("alert-success")
                            .text("Password berhasil diubah!")
                            .show();
                        
                        $("#formUpdatePassword")[0].reset();
                        $btnSubmit.prop("disabled", false).removeClass("btn-disabled");
                        
                        setTimeout(function(){
                            window.location.href = "index.php";
                        }, 2000);
                    } else {
                        var errorMsg = "Terjadi kesalahan!";
                        if(reason === "empty_fields") errorMsg = "Semua field wajib diisi!";
                        else if(reason === "password_mismatch") errorMsg = "Konfirmasi password tidak cocok!";
                        else if(reason === "wrong_password") errorMsg = "Password lama salah!";
                        else if(reason === "unauthorized") errorMsg = "Anda harus login terlebih dahulu!";
                        
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