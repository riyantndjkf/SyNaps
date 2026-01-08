<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$grupObj = new Grup();
$username = $_SESSION['username'];

$isDosen = !empty($_SESSION['npk_dosen']);
$isMahasiswa = !empty($_SESSION['nrp_mahasiswa']);

$listGrupSaya = [];
$listGrupPublik = [];

if ($isDosen) {
    $grupDibuat = $grupObj->getGrupByCreator($username);
    $grupIkut = $grupObj->getGrupByMember($username);
    
    $temp = [];
    foreach ($grupDibuat as $g) $temp[$g['idgrup']] = $g;
    foreach ($grupIkut as $g) $temp[$g['idgrup']] = $g;
    $listGrupSaya = array_values($temp);

} elseif ($isMahasiswa) {
    $listGrupSaya = $grupObj->getGrupByMember($username);
    $listGrupPublik = $grupObj->getAvailablePublicGroups($username);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Grup</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container wide">
        <h1>Halaman Grup</h1>
        <a href="index.php"><button class="btn-back">Kembali ke Home</button></a>
        
        <?php
        if (isset($_GET['status'])) {
            echo '<div style="margin-bottom: 15px; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold;';
            
            if ($_GET['status'] == 'success' || $_GET['status'] == 'join_success' || $_GET['status'] == 'keluar_success' || $_GET['status'] == 'deleted') {
                echo 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">';
                if ($_GET['status'] == 'deleted') echo "Grup berhasil dihapus.";
                else echo "Proses berhasil!";
            } else {
                echo 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">';
                if ($_GET['status'] == 'invalid_code') echo "Kode grup tidak ditemukan!";
                elseif ($_GET['status'] == 'already_member') echo "Anda sudah menjadi anggota grup ini.";
                else echo "Terjadi kesalahan!";
            }
            echo '</div>';
        }

        if ($isDosen) {
            echo '<div style="margin-bottom: 15px;">';
            echo '<a href="tambah_grup.php"><button class="btn-add">+ Buat Grup Baru</button></a>';
            echo '</div>';
        }

        if ($isMahasiswa) {
            echo '<div class="join-box" style="background:#e9ecef; padding:20px; border-radius:8px; margin-bottom:20px; border:1px solid #dee2e6;">
                <h3 style="margin-top:0;">Gabung Grup Baru</h3>
                <form method="post" action="proses_join_grup.php" class="search-box">
                    <label style="margin-right:10px;">Masukkan Kode Grup: </label>
                    <input type="text" name="kode_join" required placeholder="Contoh: AB123CD">
                    <button type="submit" class="btn-primary">Gabung</button>
                </form>
            </div>';
        }
        ?>

        <h2 class="section-title">Grup Saya</h2>
        
        <?php
        if (empty($listGrupSaya)) {
            echo "<p><i>Anda belum bergabung dengan grup manapun.</i></p>";
        } else {
            echo '<table>
                <thead>
                    <tr>
                        <th>Nama Grup</th>
                        <th>Jenis</th>
                        <th>Deskripsi</th>
                        <th style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($listGrupSaya as $g) {
                echo "<tr>";
                echo "<td><b>" . htmlentities($g['nama']) . "</b></td>";
                echo "<td>" . htmlentities($g['jenis']) . "</td>";
                echo "<td>" . htmlentities($g['deskripsi']) . "</td>";
                echo "<td>";
                echo '<div class="action-group">';

                echo '<button class="detailBtn" value="' . $g['idgrup'] . '">Detail</button>';
                echo '<button class="chatBtn btn-primary" value="' . $g['idgrup'] . '">Chat</button>';

                if ($g['username_pembuat'] != $username) {
                    echo '<button class="keluarBtn btn-update" value="' . $g['idgrup'] . '">Keluar</button>';
                } else {
                    echo '<button class="hapusGrupBtn" value="' . $g['idgrup'] . '">Hapus</button>';
                }

                echo '</div>';
                echo "</td>";
                echo "</tr>";
            }
            
            echo '</tbody></table>';
        }
        ?>

        <?php
        if ($isMahasiswa && !empty($listGrupPublik)) {
            echo '<h2 class="section-title">Grup Publik Lainnya</h2>';
            echo '<p style="font-size:13px; color:#666;"><i>Grup publik yang belum Anda ikuti. Gunakan kode untuk bergabung.</i></p>';
            
            echo '<table>
                <thead>
                    <tr>
                        <th>Nama Grup</th>
                        <th>Deskripsi</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($listGrupPublik as $gp) {
                echo "<tr>";
                echo "<td><b>" . htmlentities($gp['nama']) . "</b></td>";
                echo "<td>" . htmlentities($gp['deskripsi']) . "</td>";
                echo "<td>";
                echo "<button class='detailBtn' onclick=\"$('input[name=kode_join]').focus(); alert('Silakan masukkan kode grup ini di form atas untuk bergabung.');\">Gabung</button>";
                echo "</td>";
                echo "</tr>";
            }

            echo '</tbody></table>';
        }
        ?>
    </div>

    <script src="jquery-3.7.1.js"></script>
    <script>
    $(function() {
        $(".detailBtn").click(function() {
            if($(this).val()) {
                window.location.href = "detail_grup.php?id=" + $(this).val();
            }
        });

        $(".chatBtn").click(function() {
            window.location.href = "grup_chat.php?id_grup=" + $(this).val();
        });

        $(".keluarBtn").click(function() {
            if (confirm("Yakin ingin keluar dari grup ini?")) {
                window.location.href = "keluar_grup.php?id=" + $(this).val();
            }
        });

        $(".hapusGrupBtn").click(function() {
            if (confirm("PERINGATAN: Menghapus grup akan menghapus semua event, chat, dan member di dalamnya secara permanen.\n\nYakin ingin menghapus grup ini?")) {
                window.location.href = "hapus_grup.php?id=" + $(this).val();
            }
        });
    });
    </script>

<script src="theme.js"></script>
</body>
</html>