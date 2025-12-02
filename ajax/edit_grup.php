<?php
require_once("../security.php");
require_once("../class/grup.php");

if (empty($_SESSION['npk_dosen'])) {
    echo "error|unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "error|invalid_method";
    exit;
}

$idgrup = $_POST['idgrup'] ?? '';
$nama = trim($_POST['nama'] ?? '');
$deskripsi = $_POST['deskripsi'] ?? '';
$jenis = $_POST['jenis'] ?? '';

if (empty($nama)) {
    echo "error|nama_required";
    exit;
}

$grupObj = new Grup();

$grupLama = $grupObj->getGrup($idgrup);
if ($grupLama['username_pembuat'] != $_SESSION['username']) {
    echo "error|unauthorized_access";
    exit;
}

$arr_data = [
    'nama' => $nama,
    'deskripsi' => $deskripsi,
    'jenis' => $jenis
];

if ($grupObj->updateGrup($idgrup, $arr_data)) {
    echo "success|" . $idgrup;
} else {
    echo "error|db_failed";
}
?>
