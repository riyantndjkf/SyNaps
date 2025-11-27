<?php
require_once("security.php");
require_once("class/akun.php");

// Pastikan user login
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
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f4f4; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container { 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            width: 100%;
            max-width: 400px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        h2 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #555; }
        input[type="password"] { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box;
        }
        
        button { 
            width: 100%; 
            padding: 10px; 
            background-color: #28a745; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            font-size: 16px; 
            cursor: pointer; 
            margin-top: 10px;
        }
        button:hover { background-color: #218838; }

        .btn-back {
            background-color: #6c757d;
            margin-top: 10px;
        }
        .btn-back:hover { background-color: #5a6268; }

        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-size: 14px;}
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Ganti Password</h2>

        <div id="alert-msg" style="display:none;"></div>

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

            <button type="submit" id="btn-submit">Simpan Password</button>
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

            $btnSubmit.prop("disabled", true).css("background-color", "#ccc");

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
                        
                        // Clear form
                        $("#formUpdatePassword")[0].reset();
                        $btnSubmit.prop("disabled", false).css("background-color", "#28a745");
                        
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

</body>
</html>