<?php
require_once("parent.php");

class Dosen extends classParent {
    public function getAll() {
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

$dosenObj = new Dosen();
$dosens = $dosenObj->getAll();
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
                                    $image_path = 'images/' . $row['npk'] . '.jpg';
                                    if (file_exists($image_path)) {
                                        echo "<img src='{$image_path}' class='foto'>";
                                    } else {
                                        echo "No Image";
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['npk']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td>
                                    <a href="edit_dosen.php?npk=<?php echo $row['npk']; ?>">Edit</a> |
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