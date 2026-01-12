<?php
require_once("security.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container wide">
        <div class="theme-toggle">
            <span style="font-size:14px; font-weight:600;">Dark Mode</span>
            <label class="switch">
                <input type="checkbox" id="toggleTheme">
                <span class="slider"></span>
            </label>
        </div>
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

    <script src="js/jquery-3.7.1.js"></script>
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
                            navHtml += "<button disabled class='btn-disabled'>&laquo; Prev</button> ";
                        }
                        
                        navHtml += " <span>Halaman <strong>" + current + "</strong> dari " + max + "</span> ";
                        
                        if(current < max) {
                            var nextStart = current * pp;
                            navHtml += " <button class='page-link' data-start='"+nextStart+"'>Next &raquo;</button>";
                        } else {
                            navHtml += " <button disabled class='btn-disabled'>Next &raquo;</button>";
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

    <script src="js/theme.js"></script>
</body>
</html>
