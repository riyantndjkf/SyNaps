<?php
require_once("parent.php");

class Dosen extends classParent {
    public function __construct(){
        parent::__construct();
    }

    public function getDosen($npk = null) {
        if ($npk) {
            // kalau ada parameter NPK, ambil hanya satu dosen
            $sql = "SELECT * FROM dosen WHERE npk=?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $npk);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc(); // satu baris saja
            $stmt->close();
            return $data;
        } else {
            // kalau tidak ada parameter, ambil semua dosen
            $sql = "SELECT * FROM dosen";
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

    public function insertDosen($arr_data) {
        // ✅ cek apakah sudah ada data dosen dengan field sama persis
        $check = $this->mysqli->prepare("SELECT * FROM dosen WHERE npk=? OR nama=?");
        $check->bind_param("ss", $arr_data['npk'], $arr_data['nama']);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Data dosen tidak boleh sama dengan dosen lain!'); window.location='tambah_dosen.php';</script>";
            $check->close();
            return false;
        }
        $check->close();

        $query = "INSERT INTO dosen (npk, nama, foto_extension) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sss", $arr_data['npk'], $arr_data['nama'], $arr_data['foto_extension']);

        if ($stmt->execute()) {
            echo "<script>alert('Data dosen berhasil ditambahkan!'); window.location='dosen.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location='tambah_dosen.php';</script>";
        }

        $stmt->close();
        return $stmt->insert_id;
    } 

    public function updateDosen($npk, $arr_data) {
        $check = $this->mysqli->prepare("SELECT * FROM dosen WHERE nama=? AND npk!=?");
        $check->bind_param("ss", $arr_data['nama'], $npk);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Data dosen tidak boleh sama dengan dosen lain!'); window.location='update_dosen.php?npk=$npk';</script>";
            $check->close();
            return false;
        }
        $check->close();
            
        $query = "UPDATE dosen SET nama = ?, foto_extension = ? WHERE npk = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sss", $arr_data['nama'], $arr_data['foto_extension'], $npk);

        if ($stmt->execute()) {
            echo "<script>alert('Data dosen berhasil diperbarui!'); window.location='dosen.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location='edit_dosen.php?npk=$npk';</script>";
        }

        $stmt->close();
        return $stmt->affected_rows;
    }

    public function deleteDosen($npk) {
        $query = "DELETE FROM dosen WHERE npk = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $npk);

        if ($stmt->execute()) {
            echo "<script>alert('Data dosen berhasil dihapus!'); window.location='dosen.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data: " . $stmt->error . "'); window.location='dosen.php';</script>";
        }

        $stmt->close();
        return $stmt->affected_rows;
    }
}

// ambil semua dosen
$dosenObj = new Dosen();
$dosens = $dosenObj->getDosen();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen</title>
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
                <a href="mahasiswa.php">Kelola Mahasiswa</a><br>
            </div>
            <div class="content">
                <h2>Daftar Dosen</h2>
                <button onclick="location.href='tambah_dosen.php'">Tambah Dosen Baru</button>
                <br><br>
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th><th>NPK</th><th>Nama</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dosens as $row): ?>
                            <tr>
                                <td>
                                    <?php 
                                        if (!empty($row['foto_extension'])) {
                                            $image_path = 'images/' . $row['npk'] . '.' . $row['foto_extension'];
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
                                <td><?php echo htmlspecialchars($row['npk']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td>
                                    <a href="update_dosen.php?npk=<?php echo $row['npk']; ?>">Edit</a> |
                                    <a href="hapus_dosen.php?npk=<?php echo $row['npk']; ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
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