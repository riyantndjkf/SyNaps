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

$allMahasiswa = $mhsObj->getMahasiswa();

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
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container wide">
    <?php
    echo '<h1>Tambah Member ke Grup: ' . htmlentities($grup['nama']) . '</h1>';

    if (isset($_GET['status']) && $_GET['status'] == 'added') {
        echo '<div class="alert alert-success">Mahasiswa berhasil ditambahkan!</div>';
    }

    echo '<form method="get" action="" class="search-box">
        <input type="hidden" name="id" value="' . $idgrup . '">
        <input type="text" name="cari" id="search-input" placeholder="Cari NRP atau Nama..." value="' . htmlentities($cari) . '">
        <button type="submit" class="btn-search">Cari</button>
    </form>';

    echo '<div id="alert-msg" class="alert" style="display:none;"></div>';

    echo '<table>
        <thead>
            <tr>
                <th>NRP</th>
                <th>Nama</th>
                <th>Gender</th>
                <th style="text-align:center; width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>';

    if (empty($mahasiswas)) {
        echo '<tr><td colspan="4" style="text-align:center;">Data tidak ditemukan.</td></tr>';
    } else {
        foreach ($mahasiswas as $m) {
            $username_mhs = 's' . $m['nrp'];
            $already = $memberObj->isMember($idgrup, $username_mhs);

            echo '<tr>';
            echo '<td>' . htmlentities($m['nrp']) . '</td>';
            echo '<td>' . htmlentities($m['nama']) . '</td>';
            echo '<td>' . htmlentities($m['gender']) . '</td>';
            echo '<td style="text-align:center;">';
            
            if ($already) {
                echo '<button class="btn-disabled" disabled>Sudah Ada</button>';
            } else {
                echo '<button class="btn-add btn-add-member" data-group="' . $idgrup . '" data-user="' . $username_mhs . '">Tambah</button>';
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

    $(document).on("click", ".btn-add-member", function(){
        var idgrup = $(this).data("group");
        var user = $(this).data("user");
        var $btn = $(this);

        $.ajax({
            url: "ajax/tambah_member_mahasiswa.php",
            type: "POST",
            data: { idgrup: idgrup, user: user },
            success: function(data){
                var response = data.trim();
                var parts = response.split('|');
                var status = parts[0];

                if(status === "success"){
                    $alertMsg.removeClass("alert-danger").addClass("alert-success")
                        .text("Mahasiswa berhasil ditambahkan!")
                        .show();
                    
                    $btn.removeClass("btn-add").addClass("btn-disabled")
                        .prop("disabled", true)
                        .text("Sudah Ada");
                } else {
                    var errorMsg = "Terjadi kesalahan!";
                    if(parts[1] === "already_member") errorMsg = "Mahasiswa sudah menjadi member grup ini!";
                    else if(parts[1] === "unauthorized") errorMsg = "Anda tidak memiliki akses!";
                    else if(parts[1] === "data_incomplete") errorMsg = "Data tidak lengkap!";
                    else if(parts[1] && parts[1].includes("db_failed")) {
                        errorMsg = "Gagal menambah member: " + (parts.slice(1).join('|') || "Database error");
                    }
                    
                    $alertMsg.removeClass("alert-success").addClass("alert-danger")
                        .text(errorMsg)
                        .show();
                }
            },
            error: function(){
                $alertMsg.removeClass("alert-success").addClass("alert-danger")
                    .text("Terjadi kesalahan saat menambahkan member!")
                    .show();
            }
        });
    });
});
</script>
</body>
</html>