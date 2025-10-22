<?php
require_once("security.php");
require_once("class/dosen.php");
require_once("class/akun.php");

$dosenObj = new Dosen();
$akunObj = new Akun();

$npk = $_POST['npk']; 
$password = $_POST['password'];

$arr_akun = array(
'username' => $_POST['username'],
'password_hash' => password_hash($password, PASSWORD_DEFAULT),
'npk' => $npk,
'isadmin' => 0,
);

$arr_data = array(
    'npk' => $npk,
    'nama' => $_POST['nama'],
    'foto_extension' => null,
);

$cek = $dosenObj->getDosen($npk);

if ($cek) {
    header("Location: display_dosen.php?status=duplicate");
    exit;
}
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "images/";
    $foto_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $npk . '.' . $foto_extension;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $arr_data['foto_extension'] = $foto_extension;
    }
}

if ($dosenObj->insertDosen($arr_data) && $akunObj->insertAkunDosen($arr_akun)) {
    header("Location: display_dosen.php?status=success");
    exit;
} else {
    header("Location: display_dosen.php?status=error");
    exit;
}
?>
