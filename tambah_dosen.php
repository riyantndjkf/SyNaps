<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dosen Baru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Synaps Admin</h1>
        </div>
        <div class="main-content">
            <div class="menu">
                <h3>Menu</h3>
                <a href="dosen.php" class="active">Kelola Dosen</a>
                <a href="mahasiswa.php">Kelola Mahasiswa</a>
            </div>
            <div class="content">
                <h2>Tambah Dosen Baru</h2>
                
                <form method="post" action="proses_tambah_dosen.php" enctype="multipart/form-data">
                    <p>
                        <label for="npk">NPK</label><br>
                        <input type="text" name="npk" id="npk" required maxlength="6">
                    </p>
                    <p>
                        <label for="nama">Nama</label><br>
                        <input type="text" name="nama" id="nama" required>
                    </p>
                    <p>
                        <label for="foto">Foto</label><br>
                        <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/gif">
                    </p>
                    <p>
                        <button type="submit">Simpan</button>
                        <a href="dosen.php"><button type="button">Kembali</button></a>
                    </p>
                </form> 
            </div>
        </div>
    </div>
</body>
</html>