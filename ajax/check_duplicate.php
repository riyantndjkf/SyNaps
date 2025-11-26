<?php
require_once("../class/parent.php"); // Gunakan classParent untuk akses DB langsung biar cepat
$parent = new classParent();
$conn = new mysqli(SERVER, UID, PWD, DB);

$id = $_POST['id'];
$type = $_POST['type']; // 'dosen' atau 'mahasiswa'

if ($type == 'dosen') {
    $sql = "SELECT npk FROM dosen WHERE npk = '$id'";
} else {
    $sql = "SELECT nrp FROM mahasiswa WHERE nrp = '$id'";
}

$res = $conn->query($sql);
if ($res->num_rows > 0) {
    echo "exist";
} else {
    echo "ok";
}
?>