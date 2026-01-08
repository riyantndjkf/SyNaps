<?php
require_once("security.php");
require_once("class/member_grup.php");
require_once("class/dosen.php");
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

if (!$grup || $grup['username_pembuat'] != $_SESSION['username']) {
    header("Location: display_grup.php");
    exit;
}

$memberObj = new MemberGrup();
$dosenObj = new Dosen();

$allDosen = $dosenObj->getDosen();

$cari = isset($_GET['cari']) ? strtolower($_GET['cari']) : "";
$dosens = [];

foreach ($allDosen as $d) {
    if ($cari == "" ||
        str_contains(strtolower($d['nama']), $cari) ||
        str_contains(strtolower($d['npk']),  $cari)) {
        
        $dosens[] = $d;

    require_once("class/akun.php");
    $akunObj = new Akun();
    $dosenList = [];
    foreach ($dosens as $d) {
        $akunDosen = $akunObj->getAkunByNpk($d['npk']);
        if ($akunDosen) {
            $d['username'] = $akunDosen['username'];
            $dosenList[] = $d;
        }
    }
    $dosens = $dosenList;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Dosen ke Grup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container wide">
    <?php
    echo '<h1>Tambah Dosen ke Grup: ' . htmlentities($grup['nama']) . '</h1>';

    if (isset($_GET['status']) && $_GET['status'] == 'added') {
        echo '<div class="alert alert-success">Dosen berhasil ditambahkan!</div>';
    }

    echo '<form method="get" action="" class="search-box">
        <input type="hidden" name="id" value="' . $idgrup . '">
        <input type="text" name="cari" id="search-input" placeholder="Cari NPK atau Nama..." value="' . htmlentities($cari) . '">
        <button type="submit" class="btn-search">Cari</button>
    </form>';

    echo '<div id="alert-msg" class="alert" style="display:none;"></div>';

    echo '<table>
        <thead>
            <tr>
                <th>NPK</th>
                <th>Nama</th>
                <th style="text-align:center; width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>';

    if (empty($dosens)) {
        echo '<tr><td colspan="3" style="text-align:center;">Data tidak ditemukan.</td></tr>';
    } else {
        foreach ($dosens as $d) {
            $username_dosen = $d['username'];
            $already = $memberObj->isMember($idgrup, $username_dosen);

            echo '<tr>';
            echo '<td>' . htmlentities($d['npk']) . '</td>';
            echo '<td>' . htmlentities($d['nama']) . '</td>';
            echo '<td style="text-align:center;">';
            
            if ($already) {
                echo '<button class="btn-disabled" disabled>Sudah Ada</button>';
            } else {
                echo '<button class="btn-add btn-add-dosen" data-group="' . $idgrup . '" data-user="' . $username_dosen . '">Tambah</button>';
            }
            
            echo '</td>';
            echo '</tr>';
        }
    }

    echo '</tbody></table>';

    echo '<a href="detail_grup.php?id=' . $idgrup . '"><button type="button" class="btn-back">Kembali ke Grup</button></a>';
    ?>
</div>

<script src="jquery-3.7.1.js"></script>
<script>
$(document).ready(function(){
    var $alertMsg = $("#alert-msg");

    $(document).on("click", ".btn-add-dosen", function(){
        var idgrup = $(this).data("group");
        var user = $(this).data("user");
        var $btn = $(this);

        $.ajax({
            url: "ajax/tambah_member_dosen.php",
            type: "POST",
            data: { idgrup: idgrup, user: user },
            success: function(data){
                var response = data.trim();
                var parts = response.split('|');
                var status = parts[0];

                if(status === "success"){
                    $alertMsg.removeClass("alert-danger").addClass("alert-success")
                        .text("Dosen berhasil ditambahkan!")
                        .show();
                    
                    $btn.removeClass("btn-add").addClass("btn-disabled")
                        .prop("disabled", true)
                        .text("Sudah Ada");
                } else {
                    var errorMsg = "Terjadi kesalahan!";
                    if(parts[1] === "already_member") errorMsg = "Dosen sudah menjadi member grup ini!";
                    else if(parts[1] === "unauthorized") errorMsg = "Anda tidak memiliki akses!";
                    else if(parts[1] === "data_incomplete") errorMsg = "Data tidak lengkap!";
                    
                    $alertMsg.removeClass("alert-success").addClass("alert-danger")
                        .text(errorMsg)
                        .show();
                }
            },
            error: function(){
                $alertMsg.removeClass("alert-success").addClass("alert-danger")
                    .text("Terjadi kesalahan saat menambahkan dosen!")
                    .show();
            }
        });
    });
});
</script>
<script src="theme.js"></script>
</body>
</html>