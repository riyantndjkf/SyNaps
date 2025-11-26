<?php
require_once("../class/mahasiswa.php");

$mhsObj = new Mahasiswa();
$mahasiswas = $mhsObj->getMahasiswa();

// Ambil parameter dari AJAX
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 3;
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

// Logika Paging (Array Slicing)
$total_data = count($mahasiswas);
$display_data = array_slice($mahasiswas, $start, $per_page);

// 1. Generate Baris Tabel
foreach ($display_data as $row) {
    echo "<tr>";
    echo "<td>";
    // Perhatikan nama kolom 'foto_extention' sesuai dengan database/class Anda
    if (!empty($row['foto_extention'])) {
        $image_path = 'images/' . $row['nrp'] . '.' . $row['foto_extention'];
        // Cek file fisik (path relatif terhadap file ini)
        if (file_exists("../" . $image_path)) {
            // Src gambar relatif terhadap display_mahasiswa.php (frontend)
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
    // Format tanggal lahir menjadi d M Y
    echo "<td>" . date("d M Y", strtotime($row['tanggal_lahir'])) . "</td>";
    echo "<td>" . htmlentities($row['angkatan']) . "</td>";
    echo "<td>
            <button class='editBtn' value='" . $row['nrp'] . "'>Edit</button> | 
            <button class='hapusBtn' value='" . $row['nrp'] . "'>Hapus</button>
          </td>";
    echo "</tr>";
}

// 2. Hitung Data Paging
$max_page = ceil($total_data / $per_page);
$current_page = ($start / $per_page) + 1;

// 3. Kirim Data Paging lewat Hidden Row
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