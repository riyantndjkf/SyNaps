<?php
require_once("parent.php");

class Mahasiswa extends classParent {
    public function __construct(){
        parent::__construct();
    }

    public function getMahasiswa($nrp = null) {
        if ($nrp) {
            // kalau ada parameter NRP, ambil hanya satu mahasiswa
            $sql = "SELECT * FROM mahasiswa WHERE nrp=?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $nrp);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc(); // satu baris saja
            $stmt->close();
            return $data;
        } else {
            // kalau tidak ada parameter, ambil semua mahasiswa
            $sql = "SELECT * FROM mahasiswa";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = [];
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
            $stmt->close();
            return $data;
        }
    }

    public function insertMahasiswa($arr_data) {
    // ✅ cek apakah sudah ada data mahasiswa dengan semua field sama
    $check = $this->mysqli->prepare("SELECT * FROM mahasiswa WHERE nrp=? OR nama=?");
    $check->bind_param("ss",$arr_data['nrp'],$arr_data['nama']);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Data mahasiswa tidak boleh sama dengan mahasiswa lain!'); window.location='tambah_mahasiswa.php';</script>";
        $check->close();
        return false;
    }
    $check->close();

        $query = "INSERT INTO mahasiswa (nrp, nama, gender, tanggal_lahir, angkatan, foto_extention) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param(
            "ssssis",$arr_data['nrp'], $arr_data['nama'], $arr_data['gender'], $arr_data['tanggal_lahir'], $arr_data['angkatan'], $arr_data['foto_extention']
        );

        if ($stmt->execute()) {
            echo "<script>window.location='mahasiswa.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location='tambah_mahasiswa.php';</script>";
        }

        $stmt->close();
        return $this->mysqli->insert_id;
    }

    public function updateMahasiswa($nrp, $arr_data) {
        // ✅ cek duplikat semua field kecuali dirinya sendiri
        $check = $this->mysqli->prepare("SELECT * FROM mahasiswa WHERE nama=? AND nrp!=?");
        $check->bind_param("ss",$arr_data['nama'], $nrp);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Data mahasiswa tidak boleh sama dengan mahasiswa lain!'); window.location='update_mahasiswa.php?nrp=$nrp';</script>";
            $check->close();
            return false;
        }
        $check->close();

        $query = "UPDATE mahasiswa SET nama=?, gender=?, tanggal_lahir=?, angkatan=?, foto_extention=? WHERE nrp=?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sssiss", $arr_data['nama'], $arr_data['gender'], $arr_data['tanggal_lahir'], $arr_data['angkatan'], $arr_data['foto_extention'], $nrp);

        if ($stmt->execute()) {
            echo "<script>alert('Data mahasiswa berhasil diupdate!'); window.location='mahasiswa.php';</script>";
        } else {
            echo "<script>alert('Gagal update data: " . $stmt->error . "'); window.location='update_mahasiswa.php?nrp=$nrp';</script>";
        }

        $stmt->close();
        return $stmt->affected_rows;
    }

    public function deleteMahasiswa($nrp) {
        $query = "DELETE FROM mahasiswa WHERE nrp=?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $nrp);

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}

// ambil semua mahasiswa
$mhsObj = new Mahasiswa();
$mahasiswas = $mhsObj->getMahasiswa();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
    <style>
        table {
            border-collapse: collapse; 
            width: 100%;
        }
        th, td {
            border: 1px solid black; 
            padding: 10px; 
            text-align: center;
        }
        th {
            background-color:#f2f2f2;
        }
        img.foto {
            width: 150px; 
        }
    </style>
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
            </div>
            <div class="content">
                <h2>Daftar Mahasiswa</h2>
                <button onclick="location.href='tambah_mahasiswa.php'">Tambah Mahasiswa Baru</button>
                <br><br>
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>NRP</th>
                            <th>Nama</th>
                            <th>Gender</th>
                            <th>Tgl. Lahir</th>
                            <th>Angkatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mahasiswas as $row): ?>
                            <tr>
                                <td>
                                    <?php 
                                    if (!empty($row['foto_extention'])) {
                                            $image_path = 'images/' . $row['nrp'] . '.' . $row['foto_extention'];
                                            if (file_exists($image_path)) {
                                                echo "<img src='{$image_path}' class='foto'>";
                                            } else {
                                                echo "File not found";
                                            }
                                        }
                                        else {
                                        echo "No Image";
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['nrp']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo date("d M Y", strtotime($row['tanggal_lahir'])); ?></td>
                                <td><?php echo htmlspecialchars($row['angkatan']); ?></td>
                                <td>
                                    <a href="update_mahasiswa.php?nrp=<?php echo $row['nrp']; ?>">Edit</a> |
                                    <a href="hapus_mahasiswa.php?nrp=<?php echo $row['nrp']; ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>