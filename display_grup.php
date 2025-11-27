<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

// Cek Login umum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$grupObj = new Grup();
$username = $_SESSION['username'];

// Tentukan Peran
$isDosen = !empty($_SESSION['npk_dosen']);
$isMahasiswa = !empty($_SESSION['nrp_mahasiswa']);

$listGrupSaya = [];
$listGrupPublik = [];

if ($isDosen) {
    // LOGIKA DOSEN
    $grupDibuat = $grupObj->getGrupByCreator($username);
    $grupIkut = $grupObj->getGrupByMember($username);
    
    // Merge unik
    $temp = [];
    foreach ($grupDibuat as $g) $temp[$g['idgrup']] = $g;
    foreach ($grupIkut as $g) $temp[$g['idgrup']] = $g;
    $listGrupSaya = array_values($temp);

} elseif ($isMahasiswa) {
    // LOGIKA MAHASISWA
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
        <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f4f4; 
            padding: 20px; 
            margin: 0;
        }
        .container { 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            max-width: 900px; /* Lebih lebar sedikit karena ada tabel */
            margin: auto; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px;}
        h2.section-title { color: #555; border-bottom: 2px solid #2c62a3; padding-bottom: 5px; margin-top: 30px; font-size: 20px;}
        
        /* Style Tabel */
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 14px;}
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; color: #333; font-weight: 600; }
        tr:nth-child(even) { background-color: #f9f9f9; }

        /* Style Tombol */
        button { cursor: pointer; padding: 6px 12px; border: none; border-radius: 4px; font-size: 13px;}
        .btn-back { background-color: #6c757d; color: white; margin-bottom: 15px; }
        .btn-add { background-color: #28a745; color: white; font-size: 14px; padding: 8px 15px; }
        .detailBtn { background-color: #17a2b8; color: white; }
        .keluarBtn { background-color: #ffc107; color: black; }
        .hapusGrupBtn { background-color: #dc3545; color: white; }
        
        .join-box { 
            background: #e9ecef; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            border: 1px solid #dee2e6;
        }
        .join-box input[type="text"] { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 200px;}
        .join-box button { background-color: #007bff; color: white; padding: 8px 15px;}
    </style>
</head>

<body>
    <div class="container">
        <h1>Halaman Grup</h1>
        <a href="index.php"><button class="btn-back">Kembali ke Home</button></a>
        
        <?php
        // --- BLOK NOTIFIKASI ---
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

        // --- BLOK TOMBOL DOSEN ---
        if ($isDosen) {
            echo '<div style="margin-bottom: 15px;">';
            echo '<a href="tambah_grup.php"><button class="btn-add">+ Buat Grup Baru</button></a>';
            echo '</div>';
        }

        // --- BLOK FORM JOIN MAHASISWA ---
        if ($isMahasiswa) {
            echo '<div class="join-box">
                <h3 style="margin-top:0;">Gabung Grup Baru</h3>
                <form method="post" action="proses_join_grup.php">
                    <label>Masukkan Kode Grup: </label>
                    <input type="text" name="kode_join" required placeholder="Contoh: AB123CD">
                    <button type="submit">Gabung</button>
                </form>
            </div>';
        }
        ?>

        <h2 class="section-title">Grup Saya</h2>
        
        <?php
        // --- TABEL GRUP SAYA ---
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
                
                // Tombol Detail
                echo '<button class="detailBtn" value="' . $g['idgrup'] . '">Detail</button> ';

                // Tombol Keluar / Hapus
                if ($g['username_pembuat'] != $username) {
                    echo '<button class="keluarBtn" value="' . $g['idgrup'] . '">Keluar</button>';
                } else {
                    // Dosen/Admin Pembuat bisa hapus
                    echo '<button class="hapusGrupBtn" value="' . $g['idgrup'] . '">Hapus</button>';
                }
                echo "</td>";
                echo "</tr>";
            }
            
            echo '</tbody></table>';
        }
        ?>

        <?php
        // --- TABEL GRUP PUBLIK (Khusus Mahasiswa) ---
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
                // Tombol pancingan
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
            // Cek apakah tombol ini punya value (untuk detail grup saya)
            // atau cuma tombol dummy (untuk grup publik)
            if($(this).val()) {
                window.location.href = "detail_grup.php?id=" + $(this).val();
            }
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

</body>
</html>