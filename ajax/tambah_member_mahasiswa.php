<?php
require_once("../security.php");
require_once("../class/member_grup.php");

if (empty($_SESSION['npk_dosen'])) {
    echo "error|unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "error|invalid_method";
    exit;
}

// Ambil data SEBELUM sanitasi (gunakan $_POST asli dari superglobal)
$idgrup = isset($_POST['idgrup']) ? (int)$_POST['idgrup'] : 0;
$username_mhs = isset($_POST['user']) ? trim($_POST['user']) : '';

if (empty($idgrup) || empty($username_mhs)) {
    echo "error|data_incomplete";
    exit;
}

// Cek apakah sudah member
$memberObj = new MemberGrup();
if ($memberObj->isMember($idgrup, $username_mhs)) {
    echo "error|already_member";
    exit;
}

// Tambah member
if ($memberObj->addMember($idgrup, $username_mhs)) {
    echo "success|added";
} else {
    echo "error|db_failed";
}
?>
