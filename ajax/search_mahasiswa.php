<?php
require_once("../class/mahasiswa.php");
require_once("../class/member_grup.php");

$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';
$idgrup = isset($_GET['idgrup']) ? $_GET['idgrup'] : 0;

$mhsObj = new Mahasiswa();
$memberObj = new MemberGrup();

$allMhs = $mhsObj->getMahasiswa();
$results = [];

// Filter Manual (karena method class getMahasiswa mengambil semua)
foreach($allMhs as $m) {
    if (stripos($m['nama'], $keyword) !== false || stripos($m['nrp'], $keyword) !== false) {
        // Cek apakah sudah jadi member (username mahasiswa stored as 's'+nrp)
        $usernameMahasiswa = 's' . $m['nrp'];
        $isMember = $memberObj->isMember($idgrup, $usernameMahasiswa);
        
        echo "<tr>";
        echo "<td>" . htmlentities($m['nrp']) . "</td>";
        echo "<td>" . htmlentities($m['nama']) . "</td>";
        echo "<td>";
        if ($isMember) {
            echo "<button disabled style='background:#ccc; cursor:not-allowed;'>Sudah Join</button>";
        } else {
            // send username in data-user to be consistent with proses endpoints
            echo "<button class='btnAddMember' data-user='".$usernameMahasiswa."' style='background:#28a745; color:white;'>Tambah</button>";
        }
        echo "</td>";
        echo "</tr>";
    }
}

if (empty($allMhs)) echo "<tr><td colspan='3'>Data tidak ditemukan</td></tr>";
?>