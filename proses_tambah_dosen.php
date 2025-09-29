<?php
$mysqli = new mysqli("localhost", "root",'', "fullstack");

if ($mysqli->connect_error) {
    echo "Koneksi Gagal: " . $mysqli->connect_error;
}

// Ambil data dari form
$npk = $_POST['npk'];
$nama = $_POST['nama'];
$foto_extension = null;

// Proses upload foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $npk . '.' . $foto_extension;

    // Pindahkan file yang di-upload ke folder tujuan
    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        echo "<script>alert('Maaf, terjadi error saat mengupload file.'); window.location='tambah_dosen.php';</script>";
        exit();
    }
}

// Siapkan dan eksekusi query SQL
$query = "INSERT INTO dosen (npk, nama, foto_extension) VALUES (?, ?, ?)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss", $npk, $nama, $foto_extension);

if ($stmt->execute()) {
    echo "<script>alert('Data dosen berhasil ditambahkan!'); window.location='dosen.php';</script>";
} else {
    echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location='tambah_dosen.php';</script>";
}

$stmt->close();
$mysqli->close();
?>