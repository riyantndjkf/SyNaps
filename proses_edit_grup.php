<?php
require_once("security.php");
require_once("class/grup.php");

// Cek Dosen
if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idgrup = $_POST['idgrup'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $jenis = $_POST['jenis'];

    if (empty($nama)) {
        header("Location: edit_grup.php?id=$idgrup&status=empty");
        exit;
    }

    $grupObj = new Grup();
    
    // Validasi kepemilikan (security extra)
    $grupLama = $grupObj->getGrup($idgrup);
    if ($grupLama['username_pembuat'] != $_SESSION['username']) {
        header("Location: display_grup.php");
        exit;
    }

    $arr_data = [
        'nama' => $nama,
        'deskripsi' => $deskripsi,
        'jenis' => $jenis
    ];

    if ($grupObj->updateGrup($idgrup, $arr_data)) {
        header("Location: detail_grup.php?id=$idgrup&status=update_success");
    } else {
        header("Location: edit_grup.php?id=$idgrup&status=error");
    }
}
?>