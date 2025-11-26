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

// Cek jika dosen ini adalah pembuat grup
$isCreator = ($grup['username_pembuat'] == $_SESSION['username']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kelola Member</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 900px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; color: #333; font-weight: 600; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        button { cursor: pointer; padding: 8px 15px; border: none; border-radius: 4px; font-size: 13px; transition: background 0.3s; }
        .btn-back { background-color: #6c757d; color: white; }
        .btn-back:hover { background-color: #5a6268; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-delete:hover { background-color: #c82333; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; font-size: 14px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="container">
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
            // Tentukan Nama
            $namaTampil = '-';
            if (!empty($m['nama_mahasiswa'])) $namaTampil = $m['nama_mahasiswa'];
            else if (!empty($m['nama_dosen'])) $namaTampil = $m['nama_dosen'];

            // Tentukan Role
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