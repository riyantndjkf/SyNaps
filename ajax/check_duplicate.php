<?php
require_once("../class/dosen.php");
require_once("../class/mahasiswa.php");

$id = $_POST['id'];
$type = $_POST['type'];

if (!in_array($type, ['dosen', 'mahasiswa'])) {
    echo "error";
    exit;
}

if ($type == 'dosen') {
    $dosenObj = new Dosen();
    $data = $dosenObj->getDosen($id);
} else {
    $mahasiswaObj = new Mahasiswa();
    $data = $mahasiswaObj->getMahasiswa($id);
}

if ($data) {
    echo "exist";
} else {
    echo "ok";
}
?>