<?php
require_once("security.php");
require_once("class/member_grup.php");
require_once("class/mahasiswa.php");
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

$grupObj = new Grup();
$grup = $grupObj->getGrup($idgrup);

$memberObj = new MemberGrup();
$mhsObj = new Mahasiswa();

// Ambil semua mahasiswa
$allMahasiswa = $mhsObj->getMahasiswa();

// Filter pencarian
$cari = isset($_GET['cari']) ? strtolower($_GET['cari']) : "";
$mahasiswas = [];

foreach ($allMahasiswa as $m) {
    if ($cari == "" ||
        str_contains(strtolower($m['nama']), $cari) ||
        str_contains(strtolower($m['nrp']),  $cari)) {
        
        $mahasiswas[] = $m;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Member Mahasiswa</title>
</head>
<body>

<h1>Tambah Member Mahasiswa ke Grup: <?= htmlentities($grup['nama']); ?></h1>

<form method="get" action="">
    <input type="hidden" name="id" value="<?= $idgrup ?>">
    <input type="text" name="cari" placeholder="Cari NRP / Nama..."
           value="<?= htmlentities($cari); ?>">
    <button type="submit">Cari</button>
</form>

<br>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>NRP</th>
            <th>Nama</th>
            <th>Gender</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($mahasiswas as $m): ?>
            <?php 
                $username_mhs = $m['nrp'];  
                $already = $memberObj->isMember($idgrup, $username_mhs);
            ?>

            <tr>
                <td><?= htmlentities($m['nrp']); ?></td>
                <td><?= htmlentities($m['nama']); ?></td>
                <td><?= htmlentities($m['gender']); ?></td>

                <td>
                    <?php if ($already): ?>
                        <button disabled>Sudah Ada</button>
                    <?php else: ?>
                        <button onclick="
                            window.location.href='proses_tambah_member.php?grup=<?= $idgrup ?>&user=<?= $username_mhs ?>'
                        ">
                            Tambah
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br>
<a href="kelola_member.php?id=<?= $idgrup ?>">
    <button type="button">Kembali</button>
</a>

</body>
</html>
