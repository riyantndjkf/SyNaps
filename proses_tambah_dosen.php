<?php
require_once("dosen.php");

$dosenObj = new Dosen();
$npk = $_POST['npk']; //ini biar bisa dipakai di upload
$arr_data = array(
    'npk' => $npk,
    'nama' => $_POST['nama'],
    'foto_extension' => null,
);



// Proses upload foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $npk . '.' . $foto_extension;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $arr_data['foto_extension'] = $foto_extension;
    }
}

// update extension ke array data
$arr_data['foto_extension'] = $foto_extension;

// Panggil method insertDosen dari class
$dosenObj->insertDosen($arr_data);

echo "<script>alert('Data dosen berhasil ditambahkan!'); window.location='dosen.php';</script>";
?>
