<?php
require_once("security.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun</title>
</head>
<body>
    <h2>Form Registrasi</h2>

    <?php
    if (isset($_GET['err'])) {
        if ($_GET['err'] == "EMPTY") echo "<p style='color:red;'>Semua field wajib diisi!</p>";
        elseif ($_GET['err'] == "PWD") echo "<p style='color:red;'>Password dan konfirmasi tidak cocok!</p>";
        elseif ($_GET['err'] == "EXIST") echo "<p style='color:red;'>Username sudah terdaftar!</p>";
        elseif ($_GET['err'] == "FAIL") echo "<p style='color:red;'>Terjadi kesalahan saat menyimpan data.</p>";
    }
    ?>

    <form method="post" action="proses_registrasi.php">
        <p><label>Username</label> <input type="text" name="username" required></p>
        <p><label>Password</label> <input type="password" name="password" required></p>
        <p><label>Ulangi Password</label> <input type="password" name="password2" required></p>

        <p><label>Daftar sebagai:</label><br>
            <select name="role" required>
                <option value="">-- Pilih --</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
            </select>
        </p>

        <div id="input-nrp" style="display:none;">
            <label>NRP Mahasiswa</label> <input type="text" name="nrp">
        </div>

        <div id="input-npk" style="display:none;">
            <label>NPK Dosen</label> <input type="text" name="npk">
        </div>

        <p><button type="submit">Daftar</button></p>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>

    <script>
        const roleSelect = document.querySelector('select[name="role"]');
        const inputNRP = document.getElementById('input-nrp');
        const inputNPK = document.getElementById('input-npk');

        roleSelect.addEventListener('change', () => {
            inputNRP.style.display = 'none';
            inputNPK.style.display = 'none';

            if (roleSelect.value === 'mahasiswa') inputNRP.style.display = 'block';
            if (roleSelect.value === 'dosen') inputNPK.style.display = 'block';
        });
    </script>
</body>
</html>
