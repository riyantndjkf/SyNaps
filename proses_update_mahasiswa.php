<?php
require_once("security.php");
require_once("class/mahasiswa.php");

$mhsObj = new Mahasiswa();

$nrp = $_POST['nrp'];
$arr_data = array(
    'nama' => $_POST['nama'],
    'gender' => $_POST['gender'],
    'tanggal_lahir' => $_POST['tanggal_lahir'],
    'angkatan' => $_POST['angkatan'],
    'foto_extention' => null,
);

$oldData = $mhsObj->getMahasiswa($nrp);
$old_extention = $oldData ? $oldData['foto_extention'] : null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extention = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $nrp . '.' . $foto_extention;

    if ($old_extention) {
        $old_file = $target_dir . $nrp . '.' . $old_extention;
        if (file_exists($old_file)) unlink($old_file);
    }

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $arr_data['foto_extention'] = $foto_extention;
    }
} else {
    $arr_data['foto_extention'] = $old_extention;
}

if ($mhsObj->updateMahasiswa($nrp, $arr_data)) {
    header("Location: display_mahasiswa.php?status=success");
    exit;
} else {
    header("Location: display_mahasiswa.php?status=error");
    exit;
}

?>
