<?php
$mysqli = new mysqli("localhost", "root", '', "fullstack");
if ($mysqli->connect_error) {
    echo "Koneksi Gagal: " . $mysqli->connect_error;
}

// Validasi NRP
if (!isset($_GET['nrp'])) {
    echo "NRP tidak ditemukan!";
}
$nrp = $_GET['nrp'];

// Ambil data mahasiswa
$stmt = $mysqli->prepare("SELECT * FROM mahasiswa WHERE nrp=?");
$stmt->bind_param("s", $nrp);
$stmt->execute();
$result = $stmt->get_result();
$mahasiswa = $result->fetch_assoc();

if (!$mahasiswa) {
    die("Data mahasiswa tidak ditemukan!");
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
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
            <a href="dosen.php">Kelola Dosen</a>
            <a href="mahasiswa.php" class="active">Kelola Mahasiswa</a>
        </div>
        <div class="content">
            <h2>Edit Data Mahasiswa</h2>

            <form method="post" action="proses_update_mahasiswa.php" enctype="multipart/form-data">
                <input type="hidden" name="nrp" value="<?php echo htmlspecialchars($mahasiswa['nrp']); ?>">

                <p>
                    <label for="nama">Nama</label><br>
                    <input type="text" name="nama" id="nama" 
                           value="<?php echo htmlspecialchars($mahasiswa['nama']); ?>" required>
                </p>
                <p>
                    <label for="gender">Gender</label><br>
                    <select name="gender" id="gender" required>
                        <option value="Pria" <?php if ($mahasiswa['gender']=='Pria') echo 'selected'; ?>>Pria</option>
                        <option value="Wanita" <?php if ($mahasiswa['gender']=='Wanita') echo 'selected'; ?>>Wanita</option>
                    </select>
                </p>
                <p>
                    <label for="tanggal_lahir">Tanggal Lahir</label><br>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                           value="<?php echo $mahasiswa['tanggal_lahir']; ?>" required>
                </p>
                <p>
                    <label for="angkatan">Angkatan</label><br>
                    <input type="number" name="angkatan" id="angkatan" 
                           value="<?php echo htmlspecialchars($mahasiswa['angkatan']); ?>" required>
                </p>
                <p>
                    <label>Foto Saat Ini</label><br>
                    <?php
                    if (!empty($mahasiswa['foto_extention'])) {
                        $path = "images/" . $mahasiswa['nrp'] . "." . $mahasiswa['foto_extention'];
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
                    <label for="foto">Ganti Foto (opsional)</label><br>
                    <input type="file" name="foto" id="foto" accept="image/jpeg, image/png, image/gif">
                </p>

                <p>
                    <button type="submit" name="update">Update</button>
                    <a href="display_mahasiswa.php"><button type="button">Kembali</button></a>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
