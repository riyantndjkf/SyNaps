<?php
require_once("parent.php");

class Mahasiswa extends classParent {
    public function getAll() {
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

$mhsObj = new Mahasiswa();
$mahasiswas = $mhsObj->getAll();
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
            width: 100%; /
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
                                    $image_path = 'images/' . $row['nrp'] . '.jpg';
                                    if (file_exists($image_path)) {
                                        echo "<img src='{$image_path}' class='foto'>";
                                    } else {
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
                                    <a href="edit_mahasiswa.php?nrp=<?php echo $row['nrp']; ?>">Edit</a> |
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