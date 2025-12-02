<?php
require_once("../class/mahasiswa.php");

$mhsObj = new Mahasiswa();
$mahasiswas = $mhsObj->getMahasiswa();
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 3;
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

$total_data = count($mahasiswas);
$display_data = array_slice($mahasiswas, $start, $per_page);

foreach ($display_data as $row) {
    echo "<tr>";
    echo "<td>";
    if (!empty($row['foto_extention'])) {
        $image_path = 'images/' . $row['nrp'] . '.' . $row['foto_extention'];
        if (file_exists("../" . $image_path)) {
            echo "<img src='{$image_path}' class='foto'>";
        } else {
            echo "File not found";
        }
    } else {
        echo "No Image";
    }
    echo "</td>";
    echo "<td>" . htmlentities($row['nrp']) . "</td>";
    echo "<td>" . htmlentities($row['nama']) . "</td>";
    echo "<td>" . htmlentities($row['gender']) . "</td>";
    echo "<td>" . date("d M Y", strtotime($row['tanggal_lahir'])) . "</td>";
    echo "<td>" . htmlentities($row['angkatan']) . "</td>";
    echo "<td>
            <button class='editBtn' value='" . $row['nrp'] . "'>Edit</button> | 
            <button class='hapusBtn' value='" . $row['nrp'] . "'>Hapus</button>
          </td>";
    echo "</tr>";
}

$max_page = ceil($total_data / $per_page);
$current_page = ($start / $per_page) + 1;

if ($total_data == 0) {
    echo "<tr><td colspan='7'>Data Kosong</td></tr>";
    $max_page = 1;
    $current_page = 1;
}

echo "<tr id='pagination-row' style='display:none;' 
      data-current='$current_page' 
      data-max='$max_page' 
      data-perpage='$per_page'>
      </tr>";
?>