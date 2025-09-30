<?php
require_once("dosen.php");

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
                <a href="display_mahasiswa.php">Kelola Mahasiswa</a><br>
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
                                    <button class="editBtn" value="<?php echo $row['npk']; ?>">Edit</button>
                                    <button class="hapusBtn" value="<?php echo $row['npk']; ?>">Hapus</button>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
</body>
</html>