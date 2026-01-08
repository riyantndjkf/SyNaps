<?php
require_once("security.php");

$username = $_SESSION['username'];
$namaUser = $_SESSION['nama_lengkap'] ?? 'Saya';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Diskusi Grup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container wide">
    <h1>Diskusi Grup</h1>
    <a href="display_grup.php">
        <button class="btn-back">Kembali ke Daftar Grup</button>
    </a>

    <h2 class="section-title">Forum Diskusi</h2>

    <div class="chat-container">

        <!-- PANEL THREAD -->
        <div class="thread-panel">
            <h3>Thread</h3>

            <div class="thread open active">
                Diskusi UAS
            </div>

            <div class="thread open">
                Tanya Jawab Materi
            </div>

            <div class="thread close">
                Arsip Pengumuman
            </div>
        </div>

        <!-- PANEL CHAT -->
        <div class="chat-panel">

            <div class="chat-header">
                <b>Diskusi UAS</b>
            </div>

            <div class="chat-box">

                <!-- CHAT ORANG LAIN -->
                <div class="chat other">
                    <div class="author">Budi</div>
                    Halo semua, kapan deadline UAS?
                    <div class="time">10:30</div>
                </div>

                <!-- CHAT USER LOGIN -->
                <div class="chat me">
                    <div class="author"><?= $namaUser ?></div>
                    Deadline minggu depan.
                    <div class="time">10:32</div>
                </div>

            </div>

            <!-- INPUT CHAT (THREAD OPEN) -->
            <div class="chat-input">
                <input type="text" placeholder="Ketik pesan..." />
                <button class="btn-primary">Kirim</button>
            </div>

            <!-- JIKA THREAD CLOSE (CONTOH)
            <div class="chat-input">
                <input type="text" disabled placeholder="Thread sudah ditutup" />
                <button class="btn-disabled">Kirim</button>
            </div>
            -->

        </div>
    </div>
</div>
<script src="theme.js"></script>
</body>
</html>
