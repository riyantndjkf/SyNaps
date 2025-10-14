<?php
require_once("security.php");
require_once("class/dosen.php");

$dosenObj = new Dosen();
$npk = $_POST['npk']; 
$arr_data = array(
    'npk' => $npk,
    'nama' => $_POST['nama'],
    'foto_extension' => null,
);

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $npk . '.' . $foto_extension;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $arr_data['foto_extension'] = $foto_extension;
    }
}

$arr_data['foto_extension'] = $foto_extension;

if ($dosenObj->insertDosen($arr_data)) {
    header("Location: display_dosen.php?status=success");
    exit;
} else {
    header("Location: display_dosen.php?status=error");
    exit;
}
?>
