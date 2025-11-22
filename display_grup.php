<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");

// hanya dosen yang boleh
if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

$grupObj = new Grup();

// grup yang dosen buat
$grupDibuat = $grupObj->getGrupByCreator($_SESSION['username']);

// grup yang dosen ikuti
$grupIkut = $grupObj->getGrupByMember($_SESSION['username']);

// gabungkan (hilangkan duplikat)
$listGrup = [];
foreach ($grupDibuat as $g) $listGrup[$g['idgrup']] = $g;
foreach ($grupIkut as $g) $listGrup[$g['idgrup']] = $g;
$listGrup = array_values($listGrup);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kelola Grup</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>

<body>
    <h1>Daftar Grup</h1>

    <a href="tambah_grup.php"><button>+ Buat Grup Baru</button></a>
    <br><br>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') echo "<p style='color:green;'>Proses berhasil!</p>";
        elseif ($_GET['status'] == 'error') echo "<p style='color:red;'>Terjadi kesalahan!</p>";
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>Nama Grup</th>
                <th>Jenis</th>
                <th>Tanggal Dibentuk</th>
                <th>Kode Pendaftaran</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($listGrup)) { ?>
                <tr><td colspan="5"><i>Belum ada grup</i></td></tr>
            <?php } else { ?>
                <?php foreach ($listGrup as $g): ?>
                <tr>
                    <td><?= htmlentities($g['nama']); ?></td>
                    <td><?= htmlentities($g['jenis']); ?></td>
                    <td><?= htmlentities($g['tanggal_pembentukan']); ?></td>
                    <td><?= htmlentities($g['kode_pendaftaran']); ?></td>
                    <td>
                        <button class="detailBtn" value="<?= $g['idgrup']; ?>">Detail</button>

                        <?php if ($g['username_pembuat'] != $_SESSION['username']) { ?>
                            <button class="keluarBtn" value="<?= $g['idgrup']; ?>">Keluar</button>
                        <?php } else { ?>
                            <span style="color:gray; font-size:12px;">(Pembuat Grup)</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php } ?>
        </tbody>
    </table>

    <br>
    <a href="index.php"><button>Kembali</button></a>

    <script src="jquery-3.7.1.js"></script>
    <script>
    $(function() {
        $(".detailBtn").click(function() {
            window.location.href = "detail_grup.php?id=" + $(this).val();
        });

        $(".keluarBtn").click(function() {
            if (confirm("Yakin ingin keluar dari grup ini?")) {
                window.location.href = "keluar_grup.php?id=" + $(this).val();
            }
        });
    });
    </script>

</body>
</html>
