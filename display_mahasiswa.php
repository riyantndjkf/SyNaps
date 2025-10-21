<?php
require_once("security.php");
require_once("class/mahasiswa.php");

$mhsObj = new Mahasiswa();
$mahasiswas = $mhsObj->getMahasiswa();
$PER_PAGE = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 3;
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
                <?php
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'success') {
                        echo "<p style='color: green; text-align:center;'>Proses Berhasil!</p>";
                    } elseif ($_GET['status'] == 'error') {
                        echo "<p style='color: red; text-align:center;'>Proses Gagal!</p>";
                    } elseif ($_GET['status'] == 'duplicate') {
                        echo "<p style='color: orange; text-align:center;'>NRP sudah terdaftar, tidak bisa ditambahkan!</p>";
                    }
                }

                $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
                $display_data = array_slice($mahasiswas, $start, $PER_PAGE);
                ?>
                <button onclick="location.href='tambah_mahasiswa.php'">Tambah Mahasiswa Baru</button>
                <br><br>

                <form method="get" action="">
                    <label for="per_page">Total Data per Page: </label>
                        <select name="per_page" id="per_page">
                            <option value="3" <?php echo ($PER_PAGE == 3) ? 'selected' : ''; ?>>3</option>
                            <option value="5" <?php echo ($PER_PAGE == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?php echo ($PER_PAGE == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="15" <?php echo ($PER_PAGE == 15) ? 'selected' : ''; ?>>15</option>
                        </select>
                    <button type="submit">Tampilkan</button>
                </form>
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
                        <?php foreach ($display_data as $row): ?>
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

                <?php               
                $total_data = count($mahasiswas);
                $max_page = ceil($total_data/$PER_PAGE);
                $current_page = isset($_GET['start']) ? $_GET['start'] / $PER_PAGE + 1 : 1;

                    if($current_page==1){
                        echo "<strong>First</strong>";
                        echo " | ";
                        echo "<strong>Prev </strong>";
                        echo " | ";
                    } 
                    else{
                        echo "<a href='?start=0'>First </a>";
                        echo " | ";
                        $prev_page = ($current_page - 2) * $PER_PAGE;
                        echo "<a href='?start=$prev_page'>Prev </a>";
                    }

                    for($page=1;$page<=$max_page;$page++){
                        $offs = ($page-1) * $PER_PAGE;
                        if ($page == $current_page) {
                            echo "<strong>$page</strong> ";
                            echo "  ";
                        }
                        else {
                            echo "<a href='?start=$offs'>$page </a>";
                        }
                    }

                    if($current_page==$max_page){
                        echo " | ";
                        echo "<strong'>Next </strong>";
                        echo " | ";
                        echo "<strong>Last </strong>";
                    }
                    else{
                        $next_page = $current_page * $PER_PAGE;
                        echo " | ";
                        echo "<a href='?start=$next_page'>Next </a>";
                        echo " | ";
                        $last_page = ($max_page - 1) * $PER_PAGE;
                        echo "<a href='?start=$last_page'>Last </a>";
                    }
                ?>
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
    <br><a href="index.php"><button type="button">Kembali</button></a>
</body>
</html>