<?php
$mysqli = new mysqli("localhost", "root",'', "fullstack");

if ($mysqli->connect_error) {
    echo "Koneksi Gagal: " . $mysqli->connect_error;
}

$npk = $_GET['npk'];

// Ambil data dosen berdasarkan NPK
$stmt = $mysqli->prepare("SELECT * FROM dosen WHERE npk=?");
$stmt->bind_param("s", $npk);
$stmt->execute();
$result = $stmt->get_result();
$dosen = $result->fetch_assoc();

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dosen</title>
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
                <h2>Edit Data Dosen</h2>

                <form method="post" action="proses_update_dosen.php" enctype="multipart/form-data">

                    <input type="hidden" name="npk" value="<?php echo $dosen['npk']; ?>">
                    <p>
                        <label for="nama">Nama</label><br>
                        <input type="text" name="nama" id="nama" value="<?php echo $dosen['nama']; ?>" required>
                    </p>
                    <p>
                        <label>Foto Saat Ini</label><br>
                        <?php
                        if (!empty($dosen['foto_extension'])) {
                            $path = "images/" . $dosen['npk'] . "." . $dosen['foto_extension'];
                            if (file_exists($path)) {
                                echo "<img src='$path' width='120'><br>";
                            } else {
                                echo "File not found<br>";
                            }
                        } else {
                            echo "No Image<br>";
                        }
                        ?>
                    </p>
                    <p>
                        <label for="foto">Ganti Foto</label><br>
                        <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/jpg">
                    </p>
                    <p>
                        <button type="submit" name="update">Update</button>
                        <a href="dosen.php"><button type="button">Kembali</button></a>
                    </p>
                </form> 
            </div>
        </div>
    </div>
</body>
</html>