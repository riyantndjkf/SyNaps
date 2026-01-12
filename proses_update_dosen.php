<?php
require_once("security.php");
require_once("class/dosen.php");

$dosenObj = new Dosen();

$npk = $_POST['npk'];
$arr_data = array(
    'nama' => $_POST['nama'],
    'foto_extension' => null,
);

$oldData = $dosenObj->getDosen($npk);
$old_extension = $oldData ? $oldData['foto_extension'] : null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $npk . '.' . $foto_extension;

    if ($old_extension) {
        $old_file = $target_dir . $npk . '.' . $old_extension;
        if (file_exists($old_file)) unlink($old_file);
    }
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $arr_data['foto_extension'] = $foto_extension;
    }
} else {
    $arr_data['foto_extension'] = $old_extension;
}

if ($dosenObj->updateDosen($npk, $arr_data)) {
    header("Location: display_dosen.php?status=success");
    exit;
} else {
    header("Location: display_dosen.php?status=error");
    exit;
}

?>
