<?php
require_once("security.php");
require_once("class/member_grup.php");
require_once("class/grup.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$idgrup = $_GET['id'];

$memberObj = new MemberGrup();
$members = $memberObj->getMembersByGroup($idgrup);

$grupObj = new Grup();
$grup = $grupObj->getGrup($idgrup);

$isCreator = ($grup['username_pembuat'] == $_SESSION['username']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kelola Member</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container wide">
    <?php
    echo '<h1>Kelola Member Grup: ' . htmlentities($grup['nama']) . '</h1>';
    
    if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
        echo '<div class="alert alert-success">Member berhasil dihapus.</div>';
    }

    echo '<table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Nama</th>
                <th>Role</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>';

    if (empty($members)) {
        echo '<tr><td colspan="4" style="text-align:center;">Belum ada member</td></tr>';
    } else {
        foreach ($members as $m) {
            $namaTampil = '-';
            if (!empty($m['nama_mahasiswa'])) $namaTampil = $m['nama_mahasiswa'];
            else if (!empty($m['nama_dosen'])) $namaTampil = $m['nama_dosen'];

            $roleTampil = 'Unknown';
            if ($m['username'] == $grup['username_pembuat']) $roleTampil = "<b>Pembuat Grup</b>";
            else if (!empty($m['nrp_mahasiswa'])) $roleTampil = "Mahasiswa";
            else if (!empty($m['npk_dosen'])) $roleTampil = "Dosen";

            echo '<tr>';
            echo '<td>' . htmlentities($m['username']) . '</td>';
            echo '<td>' . htmlentities($namaTampil) . '</td>';
            echo '<td>' . $roleTampil . '</td>';
            
            echo '<td style="text-align:center;">';
            if ($isCreator && $m['username'] != $grup['username_pembuat']) {
                echo '<button class="btn-delete" onclick="if(confirm(\'Hapus member ini?\')) window.location.href=\'hapus_member.php?grup=' . $idgrup . '&user=' . $m['username'] . '\'">Hapus</button>';
            } else {
                echo '-';
            }
            echo '</td>';
            echo '</tr>';
        }
    }

    echo '</tbody></table>';

    echo '<a href="detail_grup.php?id=' . $idgrup . '"><button class="btn-back">Kembali</button></a>';
    ?>
</div>

</body>
</html>