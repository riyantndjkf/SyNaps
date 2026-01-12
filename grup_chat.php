<?php
require_once("security.php");
require_once("class/thread.php");
require_once("class/koneksi.php");

$username = $_SESSION['username'];
$namaUser = $_SESSION['nama_lengkap'] ?? 'Saya';
$id_grup = isset($_GET['idgrup']) ? (int)$_GET['idgrup'] : 0;

// Validasi ID Grup
if ($id_grup <= 0) {
    header("Location: display_grup.php");
    exit;
}

// Ambil threads untuk grup ini
$threads = [];
$activeThread = null;
try {
    $threadObj = new Thread();
    $threads = $threadObj->getThreads($id_grup);
    if (!empty($threads)) {
        $activeThread = $threads[0];
    }
} catch (Exception $e) {
    $threads = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Diskusi Grup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <h1>Diskusi Grup</h1>
    <a href="display_grup.php">
        <button class="btn-back">Kembali ke Daftar Grup</button>
    </a>

    <h2 class="section-title">Forum Diskusi</h2>

    <div class="chat-container">

        <!-- PANEL THREAD -->
        <div class="thread-panel">
            <h3>Threads</h3>
            <div id="thread-list">
                <?php if (!empty($threads)): ?>
                    <?php foreach ($threads as $thread): ?>
                        <div class="thread <?= $thread['status'] == 'Open' ? 'open' : 'close' ?> <?= ($activeThread && $activeThread['idthread'] == $thread['idthread']) ? 'active' : '' ?>" 
                             data-thread-id="<?= $thread['idthread'] ?>"
                             data-thread-creator="<?= htmlentities($thread['username_pembuat']) ?>"
                             data-thread-status="<?= htmlentities($thread['status']) ?>"
                             onclick="loadThread(<?= $thread['idthread'] ?>, '<?= htmlentities($thread['username_pembuat']) ?>', '<?= htmlentities($thread['status']) ?>')">
                            <div class="thread-title">Thread #<?= $thread['idthread'] ?></div>
                            <div class="thread-creator">oleh <?= htmlentities($thread['nama_pembuat'] ?? $thread['username_pembuat']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="font-size:12px; opacity:0.7;">Tidak ada thread</p>
                <?php endif; ?>
            </div>
            <button class="btn-primary" style="width:100%; margin-top:10px;" onclick="createNewThread()">+ Thread Baru</button>
        </div>

        <!-- PANEL CHAT -->
        <div class="chat-panel">

            <div class="chat-header">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <b id="current-thread-title"><?= $activeThread ? 'Thread #' . htmlentities($activeThread['idthread']) : 'Pilih Thread' ?></b>
                        <span id="thread-status" style="font-size:12px; opacity:0.7;">
                            <?= $activeThread ? ($activeThread['status'] == 'Open' ? '(Terbuka)' : '(Ditutup)') : '' ?>
                        </span>
                    </div>
                    <button id="btn-close-thread" class="btn-delete" style="display:none; padding:6px 12px; font-size:12px;" onclick="closeCurrentThread()">Tutup Thread</button>
                </div>
            </div>

            <div class="chat-box" id="chat-box">
                <div style="text-align:center; opacity:0.5;">Pilih thread untuk mulai chat...</div>
            </div>

            <!-- INPUT CHAT -->
            <div class="chat-input" id="chat-input-container">
                <input type="text" id="chat-message" placeholder="Ketik pesan..." />
                <button class="btn-primary" onclick="sendChat()">Kirim</button>
            </div>

        </div>
    </div>
</div>

<script src="js/jquery-3.7.1.js"></script>
<script src="js/theme.js"></script>

<script>
    // Global variables
    let currentThreadId = <?= $activeThread ? $activeThread['idthread'] : 0 ?>;
    let currentThreadCreator = '<?= $activeThread ? htmlentities($activeThread['username_pembuat']) : '' ?>';
    let currentThreadStatus = '<?= $activeThread ? htmlentities($activeThread['status']) : '' ?>';
    let lastChatId = 0;
    let pollInterval = null;
    const currentUser = '<?= htmlentities($username) ?>';
    const currentUserName = '<?= htmlentities($namaUser) ?>';
    const idGrup = <?= $id_grup ?>;

    // Load thread ke dalam chat box
    function loadThread(threadId, threadCreator, threadStatus) {
        currentThreadId = threadId;
        currentThreadCreator = threadCreator;
        currentThreadStatus = threadStatus;
        lastChatId = 0;
        
        // Update UI
        document.querySelectorAll('.thread').forEach(el => el.classList.remove('active'));
        event.target.closest('.thread').classList.add('active');
        
        document.getElementById('current-thread-title').textContent = 'Thread #' + threadId;
        document.getElementById('thread-status').textContent = threadStatus === 'Open' ? '(Terbuka)' : '(Ditutup)';
        document.getElementById('chat-box').innerHTML = '<div style="text-align:center; opacity:0.5;">Loading chat...</div>';
        
        // Tampilkan/sembunyikan button close thread
        const btnClose = document.getElementById('btn-close-thread');
        if (threadCreator === currentUser && threadStatus === 'Open') {
            btnClose.style.display = 'inline-block';
        } else {
            btnClose.style.display = 'none';
        }
        
        // Disable/enable input jika thread closed
        const chatInput = document.getElementById('chat-message');
        const btnSend = document.querySelector('.chat-input button');
        if (threadStatus === 'Close') {
            chatInput.disabled = true;
            chatInput.placeholder = 'Thread sudah ditutup';
            btnSend.disabled = true;
            btnSend.classList.add('btn-disabled');
        } else {
            chatInput.disabled = false;
            chatInput.placeholder = 'Ketik pesan...';
            btnSend.disabled = false;
            btnSend.classList.remove('btn-disabled');
        }
        
        // Load chat
        loadChats();
        
        // Start polling
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(() => {
            loadChats(true);
        }, 2000); // Poll setiap 2 detik
    }

    // Load chat dari AJAX
    function loadChats(isPolling = false) {
        if (currentThreadId <= 0) return;

        $.ajax({
            url: 'ajax/get_chats.php',
            type: 'GET',
            dataType: 'json',
            data: {
                idthread: currentThreadId,
                last_id: isPolling ? lastChatId : 0
            },
            success: function(response) {
                if (response.status === 'success') {
                    if (!isPolling) {
                        // Initial load: display semua chat
                        if (response.chats.length > 0) {
                            let html = '';
                            response.chats.forEach(chat => {
                                html += createChatBubble(chat, response.current_user);
                                lastChatId = Math.max(lastChatId, chat.idchat);
                            });
                            document.getElementById('chat-box').innerHTML = html;
                        } else {
                            // Tidak ada chat
                            document.getElementById('chat-box').innerHTML = '<div style="text-align:center; opacity:0.5; padding:20px;">Belum ada pesan. Mulai percakapan!</div>';
                        }
                        scrollToBottom();
                    } else if (isPolling && response.chats.length > 0) {
                        // Polling: append chat baru saja
                        let html = '';
                        response.chats.forEach(chat => {
                            html += createChatBubble(chat, response.current_user);
                            lastChatId = Math.max(lastChatId, chat.idchat);
                        });
                        document.getElementById('chat-box').innerHTML += html;
                        scrollToBottom();
                    }
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function(xhr) {
                console.error('AJAX error:', xhr);
            }
        });
    }

    // Create chat bubble HTML
    function createChatBubble(chat, currentUser) {
        const isMe = chat.username_pembuat === currentUser;
        const className = isMe ? 'me' : 'other';
        const nama = chat.nama_pengirim || chat.username_pembuat;
        const waktu = new Date(chat.tanggal_pembuatan).toLocaleTimeString('id-ID');
        
        return `<div class="chat ${className}">
                    <div class="author">${nama}</div>
                    <div style="word-wrap: break-word;">${escapeHtml(chat.isi)}</div>
                    <div class="time">${waktu}</div>
                </div>`;
    }

    // Send chat
    function sendChat() {
        const message = document.getElementById('chat-message').value.trim();
        
        // Validasi client-side
        if (!message) {
            alert('Pesan tidak boleh kosong!');
            return;
        }
        
        if (currentThreadId <= 0) {
            alert('Pilih thread terlebih dahulu!');
            return;
        }

        $.ajax({
            url: 'ajax/send_chat.php',
            type: 'POST',
            dataType: 'json',
            data: {
                idthread: currentThreadId,
                isi: message
            },
            success: function(response) {
                if (response.status === 'success') {
                    document.getElementById('chat-message').value = '';
                    // Chat akan muncul di polling berikutnya
                    loadChats(false); // Refresh immediate
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Gagal mengirim pesan');
                console.error('AJAX error:', xhr);
            }
        });
    }

    // Create new thread
    function createNewThread() {
        if (idGrup <= 0) {
            alert('Grup ID tidak valid');
            return;
        }

        $.ajax({
            url: 'ajax/create_thread.php',
            type: 'POST',
            dataType: 'json',
            data: {
                idgrup: idGrup
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Thread baru berhasil dibuat!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Gagal membuat thread');
                console.error('AJAX error:', xhr);
            }
        });
    }

    // Close current thread
    function closeCurrentThread() {
        if (!confirm('Yakin ingin menutup thread ini? Thread yang ditutup tidak bisa dibuka lagi.')) {
            return;
        }

        if (currentThreadId <= 0) {
            alert('Thread ID tidak valid');
            return;
        }

        $.ajax({
            url: 'ajax/close_thread.php',
            type: 'POST',
            dataType: 'json',
            data: {
                idthread: currentThreadId
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Thread berhasil ditutup');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Gagal menutup thread');
                console.error('AJAX error:', xhr);
            }
        });
    }

    // Helper function: scroll to bottom
    function scrollToBottom() {
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Helper function: escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Event listener: send chat dengan Enter key
    document.getElementById('chat-message').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendChat();
        }
    });

    // Initial load
    if (currentThreadId > 0) {
        loadChats();
        pollInterval = setInterval(() => {
            loadChats(true);
        }, 2000);
    }
</script>

</body>
</html>
