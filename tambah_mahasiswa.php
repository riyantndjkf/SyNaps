<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa Baru</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Synaps Admin</h1>
        </div>
        <div class="main-content">
            <div class="menu">
                <h3>Menu</h3>
                <a href="dosen.php">Kelola Dosen</a><br>
                <a href="mahasiswa.php">Kelola Mahasiswa</a><br>
            </div>
            <div class="content">
                <h2>Tambah Mahasiswa Baru</h2>
                
                <form method="post" action="proses_tambah_mahasiswa.php">
                    <p>
                        <label for="nrp">NRP</label><br>
                        <input type="text" name="nrp" id="nrp" required maxlength="9">
                    </p>
                    <p>
                        <label for="nama">Nama</label><br>
                        <input type="text" name="nama" id="nama" required>
                    </p>
                    <p>
                        <label>Gender</label><br>
                        <input type="radio" name="gender" value="Pria" id="pria" required> <label for="pria">Pria</label>
                        <input type="radio" name="gender" value="Wanita" id="wanita"> <label for="wanita">Wanita</label>
                    </p>
                     <p>
                        <label for="tanggal_lahir">Tanggal Lahir</label><br>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" required>
                    </p>
                    <p>
                        <label for="angkatan">Angkatan</label><br>
                        <input type="number" name="angkatan" id="angkatan" required min="1900" max="2100">
                    </p>
                    <p>
                        <label for="foto">Foto</label><br>
                        <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/gif">
                    </p>
                    <p>
                        <button type="submit">Simpan</button>
                        <a href="display.php"><button type="button">Kembali</button></a>
                    </p>
                </form> 
            </div>
        </div>
    </div>
</body>
</html>