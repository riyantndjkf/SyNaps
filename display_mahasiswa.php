<?php
require_once("security.php");
require_once("class/mahasiswa.php");

$mhsObj = new Mahasiswa();
$mahasiswas = $mhsObj->getMahasiswa();

?>

<!DOCTYPE html>
<html>
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
                <a href="display_dosen.php">Kelola Dosen</a><br>
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
                                <td><?php echo $row['nrp']; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['gender']; ?></td>
                                <td><?php echo date("d M Y", strtotime($row['tanggal_lahir'])); ?></td>
                                <td><?php echo $row['angkatan']; ?></td>
                                <td>
                                    <button class="editBtn" value="<?php echo $row['nrp']; ?>">Edit</button> |
                                    <button class="hapusBtn" value="<?php echo $row['nrp']; ?>">Hapus</button>
                                </td>
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
        var nrp = $(this).val(); 
        if(confirm("Yakin hapus mahasiswa dengan NRP " + nrp + " ?")) {
            window.location.href = "hapus_mahasiswa.php?nrp=" + nrp;
        }
    });

    $("body").on("click", ".editBtn", function(){
        var nrp = $(this).val(); 
        window.location.href = "update_mahasiswa.php?nrp=" + nrp;
    });
});
    </script>
</body>
</html>