<?php
require_once("../class/dosen.php");

$dosenObj = new Dosen();
$dosens = $dosenObj->getDosen(); // Mengambil semua data (idealnya query limit di class, tapi ini cara cepat sesuai struktur lama)

$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 3;
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

// Potong array (slicing) untuk paging
$display_data = array_slice($dosens, $start, $per_page);

// Output Baris Tabel
foreach ($display_data as $row) {
    echo "<tr>";
    echo "<td>";
    if (!empty($row['foto_extension'])) {
        $image_path = 'images/' . $row['npk'] . '.' . $row['foto_extension'];
        if (file_exists("../" . $image_path)) { // Perhatikan path relative
            echo "<img src='{$image_path}' class='foto' style='width:100px;'>";
        } else {
            echo "File not found";
        }
    } else {
        echo "No Image";
    }
    echo "</td>";
    echo "<td>" . htmlentities($row['npk']) . "</td>";
    echo "<td>" . htmlentities($row['nama']) . "</td>";
    echo "<td>
            <button class='editBtn' value='" . $row['npk'] . "'>Edit</button>
            <button class='hapusBtn' value='" . $row['npk'] . "'>Hapus</button>
          </td>";
    echo "</tr>";
}

// Output Pagination Control (Disimpan di hidden input atau elemen terpisah untuk dibaca JS)
// Kita kirimkan data pagination sebagai JSON di akhir output atau render HTML navigasi langsung
$total_data = count($dosens);
$max_page = ceil($total_data / $per_page);
$current_page = ($start / $per_page) + 1;

echo "<tr id='pagination-row' style='display:none;' 
      data-total='$total_data' 
      data-perpage='$per_page' 
      data-current='$current_page' 
      data-max='$max_page'></tr>";
?>