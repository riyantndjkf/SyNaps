<?php
$mysqli = new mysqli("localhost", "root", '', "fullstack");

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
                <a href="dosen.php">Kelola Dosen</a>
            </div>
            <div class="content">
                <h2>Daftar Mahasiswa</h2>
                <form method="GET" action="mahasiswa.php">
                    Masukkan Nama:
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($search_term); ?>">
                    <button type="submit">Cari</button>
                </form>
                <br>
                <a href="tambah_mahasiswa.php"><button>Tambah Mahasiswa Baru</button></a>
                <br><br>
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th><th>NRP</th><th>Nama</th><th>Gender</th><th>Tgl. Lahir</th><th>Angkatan</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM mahasiswa";
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
                                $image_path = 'uploads/' . $row['nrp'] . '.' . $row['foto_extension'];
                                if (file_exists($image_path)) {
                                    echo "<img src='{$image_path}' class='foto'>";
                                } else {
                                    echo "No file";
                                }
                            } else {
                                echo "No Image";
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($row['nrp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                            echo "<td>" . date("d M Y", strtotime($row['tanggal_lahir'])) . "</td>";
                            echo "<td>" . htmlspecialchars($row['angkatan']) . "</td>";
                            echo "<td>
                                  <a href='edit_mahasiswa.php?nrp=" . $row['nrp'] . "'>Edit</a> |
                                  <a href='hapus_mahasiswa.php?nrp=" . $row['nrp'] . "' onclick='return confirm(\"Yakin?\")'>Hapus</a>
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