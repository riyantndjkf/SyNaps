<?php
$mysqli = new mysqli("localhost", "root",'', "fullstack");

if ($mysqli->connect_error) {
    die("Koneksi Gagal: " . $mysqli->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                <form method="GET" action="dosen.php">
                    Masukkan Nama:
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($search_term); ?>">
                    <button type="submit">Cari</button>
                </form>
                <br>
                <a href="tambah_dosen.php"><button>Tambah Dosen Baru</button></a>
                <br><br>
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th><th>NPK</th><th>Nama</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM dosen";
                        if (!empty($search_term)) {
                            $sql .= " WHERE nama LIKE ?";
                            $param = "%" . $search_term . "%";
                        }
                        $stmt = $mysqli->prepare($sql);
                        if (!empty($search_term)) {
                            $stmt->bind_param("s", $param);
                        }
                        $stmt->execute();
                        $res = $stmt->get_result();
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>";
                            if (!empty($row['foto_extension'])) {
                                $image_path = 'images/' . $row['npk'] . '.' . $row['foto_extension'];
                                if (file_exists($image_path)) {
                                    echo "<img src='{$image_path}' class='foto'>";
                                } else {
                                    echo "No file";
                                }
                            } else {
                                echo "No Image";
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($row['npk']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>
                                  <a href='edit_dosen.php?npk=" . $row['npk'] . "'>Edit</a> |
                                  <a href='hapus_dosen.php?npk=" . $row['npk'] . "' onclick='return confirm(\"Yakin?\")'>Hapus</a>
                                  </td>";
                            echo "</tr>";
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>