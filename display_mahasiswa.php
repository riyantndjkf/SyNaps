<?php
require_once("security.php");
// Tidak perlu include class mahasiswa di sini karena diload via AJAX
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
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
            max-width: 1000px; 
            margin: auto; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        h1 { margin-top: 0; color: #333; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px; font-size: 24px; }
        h2 { color: #555; border-bottom: 2px solid #2c62a3; padding-bottom: 5px; margin-top: 10px; font-size: 20px; }

        /* Menu Navigasi */
        .menu { margin-bottom: 20px; background: #e9ecef; padding: 10px; border-radius: 4px; text-align: center; }
        .menu a { text-decoration: none; color: #007bff; font-weight: bold; margin: 0 10px; }
        .menu a:hover { text-decoration: underline; }

        /* Table */
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; vertical-align: middle; }
        th { background-color: #f8f9fa; color: #333; font-weight: 600; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        img.foto { width: 80px; border-radius: 4px; border: 1px solid #ccc; }

        /* Buttons */
        button { cursor: pointer; padding: 6px 12px; border: none; border-radius: 4px; font-size: 13px; transition: background 0.3s; }
        .btn-add { background-color: #28a745; color: white; font-size: 14px; padding: 8px 15px; margin-bottom: 15px; }
        .btn-add:hover { background-color: #218838; }

        /* Button classes from AJAX content */
        .editBtn { background-color: #ffc107; color: black; margin-right: 5px; }
        .editBtn:hover { background-color: #e0a800; }
        
        .hapusBtn { background-color: #dc3545; color: white; }
        .hapusBtn:hover { background-color: #c82333; }

        /* Paging */
        .page-link { background-color: #007bff; color: white; margin: 0 2px; }
        .page-link:hover { background-color: #0056b3; }
        #pagination-container strong { margin: 0 5px; color: #555; }

        /* Alerts */
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        
        .controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Synaps Admin</h1>
        
        <div class="menu">
            <a href="index.php">Home</a> | 
            <a href="display_dosen.php">Kelola Dosen</a> | 
            <a href="display_mahasiswa.php" style="color: #333; pointer-events: none;">Kelola Mahasiswa</a>
        </div>

        <h2>Daftar Mahasiswa</h2>
        
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') echo '<div class="alert alert-success">Proses Berhasil!</div>';
            elseif ($_GET['status'] == 'error') echo '<div class="alert alert-danger">Proses Gagal!</div>';
            elseif ($_GET['status'] == 'duplicate') echo '<div class="alert alert-warning">NRP sudah terdaftar!</div>';
        }
        ?>

        <div class="controls">
            <button class="btn-add" onclick="location.href='tambah_mahasiswa.php'">+ Tambah Mahasiswa</button>
            
            <div>
                <label for="per_page">Tampilkan: </label>
                <select name="per_page" id="per_page" style="padding: 5px;">
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 100px;">Foto</th>
                    <th>NRP</th>
                    <th>Nama</th>
                    <th>Gender</th>
                    <th>Tgl. Lahir</th>
                    <th>Angkatan</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <tr><td colspan="7">Loading data...</td></tr>
            </tbody>
        </table>

        <div id="pagination-container" style="text-align: center; margin-top: 10px;"></div>
    </div>

    <script src="jquery-3.7.1.js"></script>
    <script>
    $(document).ready(function(){
        
        function loadData(start, per_page) {
            $.ajax({
                url: "ajax/get_mahasiswa.php",
                method: "GET",
                data: { start: start, per_page: per_page },
                success: function(data) {
                    $("#table-body").html(data);
                    
                    var row = $("#pagination-row");
                    if(row.length > 0) {
                        var current = parseInt(row.attr("data-current"));
                        var max = parseInt(row.attr("data-max"));
                        var pp = parseInt(row.attr("data-perpage"));
                        
                        var navHtml = "";
                        
                        if(current > 1) {
                            var prevStart = (current - 2) * pp;
                            navHtml += "<button class='page-link' data-start='"+prevStart+"'>&laquo; Prev</button> ";
                        } else {
                            navHtml += "<button disabled style='background:#ccc; color:#666; cursor:default;'>&laquo; Prev</button> ";
                        }
                        
                        navHtml += " <span>Halaman <strong>" + current + "</strong> dari " + max + "</span> ";
                        
                        if(current < max) {
                            var nextStart = current * pp;
                            navHtml += " <button class='page-link' data-start='"+nextStart+"'>Next &raquo;</button>";
                        } else {
                            navHtml += " <button disabled style='background:#ccc; color:#666; cursor:default;'>Next &raquo;</button>";
                        }
                        
                        $("#pagination-container").html(navHtml);
                    }
                },
                error: function() {
                    $("#table-body").html("<tr><td colspan='7' style='color:red'>Gagal memuat data.</td></tr>");
                }
            });
        }

        var initialPerPage = $("#per_page").val();
        loadData(0, initialPerPage);

        $("#per_page").change(function(){
            loadData(0, $(this).val());
        });

        $("body").on("click", ".page-link", function(){
            var start = $(this).data("start");
            var perPage = $("#per_page").val();
            loadData(start, perPage);
        });

        $("body").on("click", ".hapusBtn", function(){
            var nrp = $(this).val(); 
            if(confirm("Yakin hapus mahasiswa dengan NRP " + nrp + " ?")) {
                window.location.href = "hapus_mahasiswa.php?nrp=" + nrp;
            }
        });

        $("body").on("click", ".editBtn", function(){
            var nrp = $(this).val(); 
            window.location.href = "update_mahasiswa.php?nrp=" + nrp;
        });
    });
    </script>
</body>
</html>