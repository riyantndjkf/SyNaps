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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container wide">
        <div class="theme-toggle">
            <span style="font-size:14px; font-weight:600;">Dark Mode</span>
            <label class="switch">
                <input type="checkbox" id="toggleTheme">
                <span class="slider"></span>
            </label>
        </div>
        
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
            echo '<div class="join-box" style="background:var(--bg-menu); padding:20px; border-radius:8px; margin-bottom:20px; border:1px solid var(--border-color);">
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
        
        <?php if (empty($listGrupSaya)) { ?>
            <p><i>Anda belum bergabung dengan grup manapun.</i></p>
        <?php } else { ?>
            
            <div class="container-grid">
                <?php foreach ($listGrupSaya as $g) { ?>
                    <div class="grup-card">
                        <h3><?= htmlentities($g['nama']) ?></h3>
                        <p><strong>Jenis:</strong> <?= htmlentities($g['jenis']) ?></p>
                        <p><?= htmlentities($g['deskripsi']) ?></p>
                        
                        <div class="action-group" style="margin-top:auto; padding-top:15px; border-top:1px solid var(--border-light);">
                            <button class="detailBtn" value="<?= $g['idgrup'] ?>">Detail</button>
                            <button class="chatBtn btn-primary" value="<?= $g['idgrup'] ?>">Chat</button>

                            <?php if ($g['username_pembuat'] != $username) { ?>
                                <button class="keluarBtn btn-update" value="<?= $g['idgrup'] ?>">Keluar</button>
                            <?php } else { ?>
                                <button class="hapusGrupBtn" value="<?= $g['idgrup'] ?>">Hapus</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

        <?php } ?>

        <?php if ($isMahasiswa && !empty($listGrupPublik)) { ?>
            <h2 class="section-title" style="margin-top:40px;">Grup Publik Lainnya</h2>
            <p style="font-size:13px; color:var(--text-secondary);"><i>Grup publik yang belum Anda ikuti.</i></p>
            
            <div class="container-grid">
                <?php foreach ($listGrupPublik as $gp) { ?>
                    <div class="grup-card">
                        <h3><?= htmlentities($gp['nama']) ?></h3>
                        <p><?= htmlentities($gp['deskripsi']) ?></p>
                        <div style="margin-top:auto;">
                            <button class="detailBtn" onclick="$('input[name=kode_join]').focus(); alert('Silakan masukkan kode grup ini di form atas untuk bergabung.');">Gabung</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <script src="js/jquery-3.7.1.js"></script>
    <script>
    $(function() {
        $(".detailBtn").click(function() {
            if($(this).val()) {
                window.location.href = "detail_grup.php?id=" + $(this).val();
            }
        });

        $(".chatBtn").click(function() {
            window.location.href = "grup_chat.php?idgrup=" + $(this).val();
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
    <script src="js/theme.js"></script>
</body>
</html>