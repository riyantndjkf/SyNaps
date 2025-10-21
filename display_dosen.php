<?php
require_once("security.php");
require_once("class/dosen.php");

$dosenObj = new Dosen();
$dosens = $dosenObj->getDosen();

?>

<!DOCTYPE html>
<html>
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
                <a href="display_mahasiswa.php">Kelola Mahasiswa</a><br>
            </div>
            <div class="content">
                <h2>Daftar Dosen</h2>
                <!--  Bagian Status -->
                <?php
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'success') {
                        echo "<p style='color: green; text-align:center;'>Proses Berhasil!</p>";
                    } elseif ($_GET['status'] == 'error') {
                        echo "<p style='color: red; text-align:center;'>Proses Gagal!</p>";
                    } elseif ($_GET['status'] == 'duplicate') {
                        echo "<p style='color: orange; text-align:center;'>NPK sudah terdaftar, tidak bisa ditambahkan!</p>";
                    }
                }
                ?>
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
                                <td><?php echo $row['npk']; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td>
                                    <button class="editBtn" value="<?php echo $row['npk']; ?>">Edit</button>
                                    <button class="hapusBtn" value="<?php echo $row['npk']; ?>">Hapus</button>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="jquery-3.7.1.js"></script>
    <script>
    $(function(){
    $("body").on("click", ".hapusBtn", function(){
        var npk = $(this).val(); 
        if(confirm("Yakin hapus dosen dengan NPK " + npk + " ?")) {
            window.location.href = "hapus_dosen.php?npk=" + npk;
        }
    });

    $("body").on("click", ".editBtn", function(){
        var npk = $(this).val(); 
        window.location.href = "update_dosen.php?npk=" + npk;
    });
});
    </script>
    <a href="index.php"><button type="button">Kembali</button></a>
</body>
</html>