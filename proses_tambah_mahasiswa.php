<?php
require_once("mahasiswa.php");

$mhsObj = new Mahasiswa();
$nrp = $_POST['nrp']; //ini biar bisa dipakai di upload
$arr_data = array(
    'nrp' => $nrp,
    'nama' => $_POST['nama'],
    'gender' => $_POST['gender'],
    'tanggal_lahir' => $_POST['tanggal_lahir'],
    'angkatan' => $_POST['angkatan'],
    'foto_extention' => null,
);



// proses upload foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extention = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $nrp . '.' . $foto_extention;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $arr_data['foto_extention'] = $foto_extention;
    }
}

;

if ($mhsObj->insertMahasiswa($arr_data)) {
    header("Location: display_mahasiswa.php?status=success");
    exit;
} else {
    header("Location: display_mahasiswa.php?status=error");
    exit;
}
?>