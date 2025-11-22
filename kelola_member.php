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
</head>
<body>

<h1>Kelola Member Grup: <?= htmlentities($grup['nama']); ?></h1>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nama</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($members as $m): ?>
            <tr>
                <td><?= htmlentities($m['username']); ?></td>

                <td>
                    <?php 
                        if (!empty($m['nama_mahasiswa'])) echo $m['nama_mahasiswa'];
                        else if (!empty($m['nama_dosen'])) echo $m['nama_dosen'];
                        else echo '-';
                    ?>
                </td>

                <td>
                    <?php 
                        if ($m['username'] == $grup['username_pembuat']) echo "<b>Pembuat Grup</b>";
                        else if ($m['nrp_mahasiswa']) echo "Mahasiswa";
                        else echo "Dosen";
                    ?>
                </td>

                <td>
                    <?php if ($isCreator && $m['username'] != $grup['username_pembuat']): ?>
                        <button onclick="
                            if (confirm('Hapus member ini?'))
                                window.location.href='hapus_member.php?grup=<?= $idgrup ?>&user=<?= $m['username'] ?>'
                        ">
                            Hapus
                        </button>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br>
<a href="detail_grup.php?id=<?= $idgrup ?>">
    <button>Kembali</button>
</a>

</body>
</html>
