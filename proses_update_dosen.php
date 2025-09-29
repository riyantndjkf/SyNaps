<?php
$mysqli = new mysqli("localhost", "root", '', "fullstack");

if ($mysqli->connect_error) {
    echo "Koneksi Gagal: " . $mysqli->connect_error;
    exit();
}

// Ambil data dari form
$npk = $_POST['npk']; // tetap kirim hidden input npk dari form
$nama = $_POST['nama'];
$foto_extension = null;

// Ambil foto_extension lama dari database (jika ada)
$stmt = $mysqli->prepare("SELECT foto_extension FROM dosen WHERE npk=?");
$stmt->bind_param("s", $npk);
$stmt->execute();
$result = $stmt->get_result();
$oldData = $result->fetch_assoc();
$old_extension = $oldData ? $oldData['foto_extension'] : null;
$stmt->close();

// Proses upload foto baru (jika ada)
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $npk . '.' . $foto_extension;

    // Hapus file lama jika ada
    if ($old_extension) {
        $old_file = $target_dir . $npk . '.' . $old_extension;
        if (file_exists($old_file)) {
            unlink($old_file);
        }
    }

    // Upload file baru
    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        echo "<script>alert('Maaf, terjadi error saat mengupload file.'); window.location='update_dosen.php?npk=$npk';</script>";
        exit();
    }
} else {
    // Jika tidak upload foto baru, pakai extension lama
    $foto_extension = $old_extension;
}

// Siapkan dan eksekusi query SQL
$query = "UPDATE dosen SET nama=?, foto_extension=? WHERE npk=?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss", $nama, $foto_extension, $npk);

if ($stmt->execute()) {
    echo "<script>alert('Data dosen berhasil diperbarui!'); window.location='dosen.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location='update_dosen.php?npk=$npk';</script>";
}

$stmt->close();
$mysqli->close();
?>
