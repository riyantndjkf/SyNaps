<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");
require_once("class/event.php");

if (empty($_SESSION['npk_dosen'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: display_grup.php");
    exit;
}

$idgrup = $_GET['id'];

$grupObj = new Grup();
$memberObj = new MemberGrup();
$eventObj = new Event();

// data grup
$grup = $grupObj->getGrup($idgrup);

if (!$grup) {
    header("Location: display_grup.php");
    exit;
}

// daftar event
$events = $eventObj->getEventByGroup($idgrup);

// daftar member
$members = $memberObj->getMembersByGroup($idgrup);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Grup</title>

    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>

<body>

<h1>Detail Grup: <?= htmlentities($grup['nama']); ?></h1>

<a href="display_grup.php"><button>Kembali</button></a>
<br><br>

<h3>Informasi Grup</h3>
<table>
    <tr><th>Nama</th><td><?= htmlentities($grup['nama']); ?></td></tr>
    <tr><th>Deskripsi</th><td><?= nl2br(htmlentities($grup['deskripsi'])); ?></td></tr>
    <tr><th>Jenis Grup</th><td><?= htmlentities($grup['jenis']); ?></td></tr>
    <tr><th>Tanggal Dibentuk</th><td><?= htmlentities($grup['tanggal_pembentukan']); ?></td></tr>
    <tr><th>Kode Pendaftaran</th><td><?= htmlentities($grup['kode_pendaftaran']); ?></td></tr>
</table>

<br>

<!-- MENU -->
<h3>Menu Grup</h3>
<button onclick="location.href='kelola_member.php?id=<?= $idgrup ?>'">Kelola Member</button>
<button onclick="location.href='tambah_member.php?id=<?= $idgrup ?>'">Tambah Member Mahasiswa</button>
<button onclick="location.href='tambah_event.php?id=<?= $idgrup ?>'">Tambah Event</button>

<br><br>

<!-- EVENT SECTION -->
<h2>Daftar Event</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Jenis</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Poster</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($events)) { ?>
            <tr><td colspan="6"><i>Belum ada event</i></td></tr>
        <?php } else { ?>
            <?php foreach ($events as $ev): ?>
                <tr>
                    <!-- JUDUL -->
                    <td><?= htmlentities($ev['judul']); ?></td>

                    <!-- JENIS -->
                    <td><?= htmlentities($ev['jenis']); ?></td>

                    <!-- TANGGAL -->
                    <td><?= htmlentities($ev['tanggal']); ?></td>

                    <!-- KETERANGAN -->
                    <td><?= nl2br(htmlentities($ev['keterangan'])); ?></td>

                    <!-- POSTER -->
                    <td>
                        <?php if (!empty($ev['poster_extension'])): ?>
                            <img src="images/event/<?= $ev['judul_slug'] . '.' . $ev['poster_extension']; ?>" width="120">
                        <?php else: ?>
                            <i>Tidak ada</i>
                        <?php endif; ?>
                    </td>

                    <!-- AKSI -->
                    <td>
                        <button onclick="window.location.href='update_event.php?id=<?= $ev['idevent'] ?>'">
                            Edit
                        </button>

                        <button onclick="
                            if (confirm('Hapus event ini?')) {
                                window.location.href='hapus_event.php?id=<?= $ev['idevent'] ?>&grup=<?= $ev['idgrup'] ?>';
                            }
                        ">
                            Hapus
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php } ?>
    </tbody>
</table>
<br><br>

<!-- MEMBER SECTION -->
<h2>Daftar Member</h2>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Nama</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php if (empty($members)) { ?>
            <tr><td colspan="3"><i>Belum ada member</i></td></tr>
        <?php } else { ?>
            <?php foreach ($members as $m): ?>
                <tr>
                    <td><?= htmlentities($m['username']); ?></td>
                    <td>
                        <?php 
                            if (!empty($m['nama_mahasiswa'])) echo $m['nama_mahasiswa'];
                            else if (!empty($m['nama_dosen'])) echo $m['nama_dosen'];
                            else echo "-";
                        ?>
                    </td>
                    <td>
                        <?php 
                            if (!empty($m['nrp_mahasiswa'])) echo "Mahasiswa";
                            else if (!empty($m['npk_dosen'])) echo "Dosen";
                            else echo "Unknown";
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php } ?>
    </tbody>
</table>

<script src="jquery-3.7.1.js"></script>

<script>
$(function(){
    $(".hapusEventBtn").click(function(){
        if (confirm("Yakin hapus event ini?")) {
            window.location.href = "hapus_event.php?id=" + $(this).val();
        }
    });
});
</script>

</body>
</html>
