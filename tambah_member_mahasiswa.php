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
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 900px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        .search-box { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-box input { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-search { background-color: #007bff; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-search:hover { background-color: #0056b3; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; color: #333; font-weight: 600; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-add { background-color: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .btn-add:hover { background-color: #218838; }
        .btn-disabled { background-color: #ccc; color: #666; border: none; padding: 6px 12px; border-radius: 4px; cursor: not-allowed; }
        .btn-back { background-color: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        .btn-back:hover { background-color: #5a6268; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; font-size: 14px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="container">
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

    echo '<div id="alert-msg" style="display:none; margin-bottom:15px; padding:10px; border-radius:4px; text-align:center; font-weight:bold;"></div>';

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
            $username_mhs = 's' . $m['nrp'];  // Tambah prefix 's' untuk username
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

    // Tambah member via AJAX
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
                    
                    // Ubah tombol menjadi disabled
                    $btn.removeClass("btn-add").addClass("btn-disabled")
                        .prop("disabled", true)
                        .text("Sudah Ada");
                } else {
                    var errorMsg = "Terjadi kesalahan!";
                    if(parts[1] === "already_member") errorMsg = "Mahasiswa sudah menjadi member grup ini!";
                    else if(parts[1] === "unauthorized") errorMsg = "Anda tidak memiliki akses!";
                    else if(parts[1] === "data_incomplete") errorMsg = "Data tidak lengkap!";
                    else if(parts[1] && parts[1].includes("db_failed")) {
                        // Jika ada error detail dari database
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

<style>
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>

</body>
</html>