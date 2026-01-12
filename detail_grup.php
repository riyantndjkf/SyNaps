<?php
require_once("security.php");
require_once("class/grup.php");
require_once("class/member_grup.php");
require_once("class/event.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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

$grup = $grupObj->getGrup($idgrup);

if (!$grup) {
    header("Location: display_grup.php");
    exit;
}

$events = $eventObj->getEventByGroup($idgrup);
$members = $memberObj->getMembersByGroup($idgrup);

$isPembuat = ($grup['username_pembuat'] == $_SESSION['username']);
$isDosenMember = false;
if (!empty($_SESSION['npk_dosen'])) {
    $isDosenMember = $memberObj->isMember($idgrup, $_SESSION['username']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Grup</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Tambah Anggota</h2>
    <input type="text" id="txtCari" placeholder="Cari Nama / NRP..." style="width:70%; padding:8px;">
    <br><br>
    <table style="width:100%">
        <thead><tr><th>NRP</th><th>Nama</th><th>Aksi</th></tr></thead>
        <tbody id="searchResult">
            </tbody>
    </table>
  </div>
</div>

<div class="container wide">
    <div class="theme-toggle">
        <span style="font-size:14px; font-weight:600;">Dark Mode</span>
        <label class="switch">
            <input type="checkbox" id="toggleTheme">
            <span class="slider"></span>
        </label>
    </div>
    <h1>Detail Grup: <?php echo htmlentities($grup['nama']); ?></h1>

    <a href="display_grup.php"><button class="btn-back">Kembali</button></a>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success' || $_GET['status'] == 'update_success' || $_GET['status'] == 'event_deleted') {
            echo '<div class="alert alert-success">Proses berhasil!</div>';
        } elseif ($_GET['status'] == 'error') {
            echo '<div class="alert alert-danger">Terjadi kesalahan!</div>';
        }
    }
    ?>

    <h3>Informasi Grup</h3>
    <table>
        <?php
        echo '<tr><th>Nama Grup</th><td>' . htmlentities($grup['nama']) . '</td></tr>';
        echo '<tr><th>Deskripsi</th><td>' . nl2br(htmlentities($grup['deskripsi'])) . '</td></tr>';
        echo '<tr><th>Jenis</th><td>' . htmlentities($grup['jenis']) . '</td></tr>';
        echo '<tr><th>Tanggal Dibentuk</th><td>' . date('d M Y', strtotime($grup['tanggal_pembentukan'])) . '</td></tr>';
        echo '<tr><th>Kode Pendaftaran</th><td><span style="font-family:monospace; font-weight:bold; font-size:1.1em;">' . htmlentities($grup['kode_pendaftaran']) . '</span></td></tr>';
        ?>
    </table>

    <?php
    if ($isPembuat) {
        echo '<div class="admin-menu-box">';
        echo '<h3 style="margin-top:0;">Menu Admin Grup</h3>';
        echo '<button class="btn-menu" onclick="location.href=\'edit_grup.php?id=' . $idgrup . '\'">Edit Informasi Grup</button> ';
        echo '<button class="btn-menu" onclick="location.href=\'kelola_member.php?id=' . $idgrup . '\'">Kelola Member</button> ';
        echo '<button class="btn-menu" onclick="location.href=\'tambah_member_mahasiswa.php?id=' . $idgrup . '\'">+ Member Mahasiswa</button> ';
        echo '<button class="btn-menu" onclick="location.href=\'tambah_member_dosen.php?id=' . $idgrup . '\'">+ Member Dosen</button> ';
        echo '</div>';
    }

    if ($isPembuat || $isDosenMember) {
        echo '<div style="margin-bottom:20px;">';
        echo '<button class="btn-menu" onclick="location.href=\'tambah_event.php?id=' . $idgrup . '\'">+ Tambah Event</button>';
        echo '</div>';
    }
    ?>

    <h2>Daftar Event</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 20%;">Judul</th>
                <th style="width: 10%;">Jenis</th>
                <th style="width: 15%;">Tanggal</th>
                <th>Keterangan</th>
                <th style="width: 15%;">Poster</th>
                <th style="width: 15%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (empty($events)) {
                echo '<tr><td colspan="6" style="text-align:center; font-style:italic; color:#777;">Belum ada event di grup ini.</td></tr>';
            } else {
                foreach ($events as $ev) {
                    echo '<tr>';
                    echo '<td><b>' . htmlentities($ev['judul']) . '</b></td>';
                    echo '<td>' . htmlentities($ev['jenis']) . '</td>';
                    echo '<td>' . date('d M Y H:i', strtotime($ev['tanggal'])) . '</td>';
                    echo '<td>' . nl2br(htmlentities($ev['keterangan'])) . '</td>';
                    
                    echo '<td style="text-align:center;">';
                    if (!empty($ev['poster_extension'])) {
                        echo '<img src="images/event/' . $ev['judul_slug'] . '.' . $ev['poster_extension'] . '" style="width:100px; border-radius:4px; border:1px solid #ddd;">';
                    } else {
                        echo '<span style="color:#999;">-</span>';
                    }
                    echo '</td>';

                    echo '<td>';
                    if ($isPembuat || $isDosenMember) {
                        echo '<button class="btn-edit" onclick="window.location.href=\'update_event.php?id=' . $ev['idevent'] . '\'">Edit</button> ';
                        echo '<button class="btn-delete" onclick="if (confirm(\'Yakin ingin menghapus event ini?\')) { window.location.href=\'hapus_event.php?id=' . $ev['idevent'] . '&grup=' . $ev['idgrup'] . '\'; }">Hapus</button>';
                    } else {
                        echo '<span style="color:#999; font-size:12px;">(Read Only)</span>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>

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
            <?php
            if (empty($members)) {
                echo '<tr><td colspan="3" style="text-align:center;">Belum ada member</td></tr>';
            } else {
                foreach ($members as $m) {
                    echo '<tr>';
                    echo '<td>' . htmlentities($m['username']) . '</td>';
                    
                    echo '<td>';
                    if (!empty($m['nama_mahasiswa'])) echo $m['nama_mahasiswa'];
                    else if (!empty($m['nama_dosen'])) echo $m['nama_dosen'];
                    else echo "-";
                    echo '</td>';

                    echo '<td>';
                    if (!empty($m['nrp_mahasiswa'])) echo '<span class="badge-mahasiswa">Mahasiswa</span>';
                    else if (!empty($m['npk_dosen'])) echo '<span class="badge-dosen">Dosen</span>';
                    else echo "Unknown";
                    echo '</td>';
                    
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>
<script src="js/jquery-3.7.1.js"></script>
<script>
$(document).ready(function(){
    var modal = $("#myModal");
    var idgrup = "<?php echo $idgrup; ?>";

    $(".btn-tambah-member").click(function(){ 
        modal.show(); 
        $("#txtCari").val("").trigger("keyup"); 
    });

    $(".close").click(function(){ modal.hide(); });

    $("#txtCari").keyup(function(){
        var keyword = $(this).val();
        $.get("ajax/search_mahasiswa.php", { cari: keyword, idgrup: idgrup })
         .done(function(data){
             $("#searchResult").html(data);
         });
    });

    $("body").on("click", ".btnAddMember", function(){
        var nrp = $(this).data("nrp");
        $.get("proses_tambah_member.php", { grup: idgrup, user: nrp }) 
         .done(function(){
             alert("Berhasil ditambahkan!");
             $("#txtCari").trigger("keyup");
             location.reload(); 
         });
    });
});
</script>

<script src="js/theme.js"></script>
</body>
</html>
