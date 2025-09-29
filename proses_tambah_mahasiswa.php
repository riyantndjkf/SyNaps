<?php
$mysqli = new mysqli("localhost", "root", '', "fullstack");

if ($mysqli->connect_error) {
    die("Koneksi Gagal: " . $mysqli->connect_error);
}

// Ambil data dari form
$nrp = $_POST['nrp'];
$nama = $_POST['nama'];
$gender = $_POST['gender'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$angkatan = $_POST['angkatan'];
$foto_extention = null;

// Proses upload foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extention = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $nrp . '.' . $foto_extention;

    // Pindahkan file yang di-upload ke folder tujuan
    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        echo "<script>alert('Maaf, terjadi error saat mengupload file.'); window.location='tambah_mahasiswa.php';</script>";
        exit();
    }
}

// Siapkan dan eksekusi query SQL
$query = "INSERT INTO mahasiswa (nrp, nama, gender, tanggal_lahir, angkatan, foto_extention) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ssssis", $nrp, $nama, $gender, $tanggal_lahir, $angkatan, $foto_extention);

if ($stmt->execute()) {
    echo "<script>window.location='mahasiswa.php';</script>";
} else {
    echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location='tambah_mahasiswa.php';</script>";
}

$stmt->close();
$mysqli->close();
?>