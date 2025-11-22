<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$jenis = $_POST['jenis'];

if (empty($nama)) {
    header("Location: tambah_grup.php?status=empty");
    exit;
}

function generateKode($len = 8) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $res = "";
    for ($i = 0; $i < $len; $i++) {
        $res .= $chars[random_int(0, strlen($chars)-1)];
    }
    return $res;
}

$kode = generateKode();

$grupObj = new Grup();

$arr_data = [
    "username_pembuat" => $_SESSION['username'],
    "nama" => $nama,
    "deskripsi" => $deskripsi,
    "tanggal_pembentukan" => date("Y-m-d"),
    "jenis" => $jenis,
    "kode_pendaftaran" => $kode
];

if ($grupObj->insertGrup($arr_data)) {

    // Ambil id grup baru
    $all = $grupObj->getGrup();
    $idbaru = null;
    foreach ($all as $g) {
        if ($g['kode_pendaftaran'] == $kode) {
            $idbaru = $g['idgrup'];
            break;
        }
    }

    // Tambahkan pembuat sebagai member
    if ($idbaru) {
        $mg = new MemberGrup();
        $mg->addMember($idbaru, $_SESSION['username']);
    }

    header("Location: display_grup.php?status=success");
    exit;
} else {
    header("Location: display_grup.php?status=error");
    exit;
}
?>
