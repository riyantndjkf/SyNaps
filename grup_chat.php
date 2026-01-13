<?php
require_once("security.php");
require_once("class/thread.php");
require_once("class/koneksi.php");

$username = $_SESSION['username'];
$namaUser = $_SESSION['nama_lengkap'] ?? 'Saya';
$id_grup = isset($_GET['idgrup']) ? (int)$_GET['idgrup'] : 0;

if ($id_grup <= 0) {
    header("Location: display_grup.php");
    exit;
}

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
    <style>
        /* CSS Tambahan khusus Thread List agar rapi di sidebar sempit */
        .thread {
            padding: 10px;
            margin-bottom: 8px;
            background: var(--bg-container);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }
        .thread:hover { background: var(--bg-menu); }
        .thread.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .thread.active .thread-title, .thread.active .thread-creator { color: white; }
        .thread-title { font-weight: bold; font-size: 13px; margin-bottom: 2px; }
        .thread-creator { font-size: 11px; opacity: 0.8; }
    </style>
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
    
    <div style="display:flex; align-items:center; gap: 15px; margin-bottom:15px; padding-right: 60px;">
        <a href="display_grup.php" style="text-decoration:none;">
            <button class="btn-back" style="margin:0;">&larr; Kembali</button>
        </a>

        <h1 style="margin:0; border:none; padding:0; text-align:left; font-size:24px;">Diskusi Grup</h1>
    </div>

    <div class="chat-wrapper">

        <div class="chat-sidebar" id="chat-sidebar">
            <div style="padding:15px;">
                <h3 style="font-size:16px; margin-top:0;">Threads</h3>
                <button class="btn-primary" style="width:100%; margin-bottom:15px; font-size:12px;" onclick="createNewThread()">+ Thread Baru</button>
                
                <div id="thread-list">
                    <?php if (!empty($threads)): ?>
                        <?php foreach ($threads as $thread): ?>
                            <div class="thread <?= $thread['status'] == 'Open' ? 'open' : 'close' ?> <?= ($activeThread && $activeThread['idthread'] == $thread['idthread']) ? 'active' : '' ?>" 
                                 data-thread-id="<?= $thread['idthread'] ?>"
                                 data-thread-creator="<?= htmlentities($thread['username_pembuat']) ?>"
                                 data-thread-status="<?= htmlentities($thread['status']) ?>"
                                 onclick="loadThread(<?= $thread['idthread'] ?>, '<?= htmlentities($thread['username_pembuat']) ?>', '<?= htmlentities($thread['status']) ?>')">
                                <div class="thread-title">#<?= $thread['idthread'] ?></div>
                                <div class="thread-creator"><?= htmlentities($thread['nama_pembuat'] ?? $thread['username_pembuat']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="font-size:12px; opacity:0.7; text-align:center;">Tidak ada thread</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="chat-main">
            <div class="chat-header" style="padding:10px 15px; border-bottom:1px solid var(--border-color); background:var(--bg-table-head); height: 50px; display:flex; align-items:center; justify-content:space-between;">
                <div style="overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">
                    <b id="current-thread-title"><?= $activeThread ? 'Thread #' . htmlentities($activeThread['idthread']) : 'Pilih Thread' ?></b>
                    <span id="thread-status" style="font-size:11px; opacity:0.7; margin-left:5px;">
                        <?= $activeThread ? ($activeThread['status'] == 'Open' ? '(Terbuka)' : '(Ditutup)') : '' ?>
                    </span>
                </div>
                <button id="btn-close-thread" class="btn-delete" style="display:none; padding:4px 8px; font-size:11px; margin:0;" onclick="closeCurrentThread()">Tutup</button>
            </div>

            <div class="chat-messages" id="chat-box">
                <div style="text-align:center; opacity:0.5; margin-top:20px;">Pilih thread untuk mulai chat...</div>
            </div>

            <div class="chat-input-area">
                <textarea id="chat-message" rows="1" placeholder="Ketik pesan..."></textarea>
                <button class="btn-primary" style="margin:0; border-radius:20px; padding:10px 20px;" onclick="sendChat()">Kirim</button>
            </div>
        </div>

    </div>
</div>

<script src="js/jquery-3.7.1.js"></script>
<script src="js/theme.js"></script>

<script>
    let currentThreadId = <?= $activeThread ? $activeThread['idthread'] : 0 ?>;
    let currentThreadCreator = '<?= $activeThread ? htmlentities($activeThread['username_pembuat']) : '' ?>';
    let currentThreadStatus = '<?= $activeThread ? htmlentities($activeThread['status']) : '' ?>';
    let lastChatId = 0;
    let pollInterval = null;
    const currentUser = '<?= htmlentities($username) ?>';
    
    function loadThread(threadId, threadCreator, threadStatus) {
        currentThreadId = threadId;
        currentThreadCreator = threadCreator;
        currentThreadStatus = threadStatus;
        lastChatId = 0;
        
        document.querySelectorAll('.thread').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
        
        document.getElementById('current-thread-title').textContent = 'Thread #' + threadId;
        document.getElementById('thread-status').textContent = threadStatus === 'Open' ? '(Terbuka)' : '(Ditutup)';
        document.getElementById('chat-box').innerHTML = '<div style="text-align:center; opacity:0.5; padding:20px;">Loading...</div>';
        
        const btnClose = document.getElementById('btn-close-thread');
        if (threadCreator === currentUser && threadStatus === 'Open') {
            btnClose.style.display = 'block';
        } else {
            btnClose.style.display = 'none';
        }
        
        const chatInput = document.getElementById('chat-message');
        const btnSend = document.querySelector('.chat-input-area button');
        
        if (threadStatus === 'Close') {
            chatInput.disabled = true;
            chatInput.placeholder = 'Thread sudah ditutup';
            btnSend.disabled = true;
            btnSend.style.backgroundColor = '#ccc';
        } else {
            chatInput.disabled = false;
            chatInput.placeholder = 'Ketik pesan...';
            btnSend.disabled = false;
            btnSend.style.backgroundColor = '';
        }
        
        loadChats();
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(() => { loadChats(true); }, 2000);
        if(window.innerWidth <= 768) {
             document.querySelector('.chat-main').scrollIntoView({behavior: 'smooth'});
        }
    }

    function loadChats(isPolling = false) {
        if (currentThreadId <= 0) return;

        $.ajax({
            url: 'ajax/get_chats.php',
            type: 'GET',
            dataType: 'json',
            data: { idthread: currentThreadId, last_id: isPolling ? lastChatId : 0 },
            success: function(response) {
                if (response.status === 'success') {
                    if (response.chats.length > 0) {
                        let html = '';
                        response.chats.forEach(chat => {
                            html += createChatBubble(chat, response.current_user);
                            lastChatId = Math.max(lastChatId, chat.idchat);
                        });
                        
                        const chatBox = document.getElementById('chat-box');
                        const isScrolledToBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 100;

                        if (!isPolling) {
                            chatBox.innerHTML = html;
                            scrollToBottom();
                        } else {
                            chatBox.insertAdjacentHTML('beforeend', html);
                            if(isScrolledToBottom) scrollToBottom();
                        }
                    } else if (!isPolling) {
                        document.getElementById('chat-box').innerHTML = '<div style="text-align:center; opacity:0.5; padding:20px;">Belum ada pesan.</div>';
                    }
                }
            }
        });
    }

    function createChatBubble(chat, currentUser) {
        const isMe = chat.username_pembuat === currentUser;
        const className = isMe ? 'me' : 'you'; 
        const nama = chat.nama_pengirim || chat.username_pembuat;
        const date = new Date(chat.tanggal_pembuatan);
        const waktu = date.getHours() + ':' + (date.getMinutes()<10?'0':'') + date.getMinutes();
        
        return `<div class="bubble ${className}" style="margin-bottom:10px;">
                    <div style="font-weight:bold; font-size:11px; margin-bottom:2px;">${nama}</div>
                    <div>${escapeHtml(chat.isi)}</div>
                    <div style="font-size:10px; opacity:0.6; text-align:right; margin-top:4px;">${waktu}</div>
                </div>`;
    }

    function sendChat() {
        const messageInput = document.getElementById('chat-message');
        const message = messageInput.value.trim();
        
        if (!message || currentThreadId <= 0) return;

        $.ajax({
            url: 'ajax/send_chat.php',
            type: 'POST',
            dataType: 'json',
            data: { idthread: currentThreadId, isi: message },
            success: function(response) {
                if (response.status === 'success') {
                    messageInput.value = '';
                    loadChats(false);
                    scrollToBottom();
                } else {
                    alert('Gagal: ' + response.message);
                }
            }
        });
    }
    
    function createNewThread() {
        const idGrup = <?= $id_grup ?>;
        $.post('ajax/create_thread.php', {idgrup: idGrup}, function(res){
            location.reload();
        }, 'json');
    }

    function closeCurrentThread() {
        if(confirm('Tutup thread ini?')) {
            $.post('ajax/close_thread.php', {idthread: currentThreadId}, function(res){
                location.reload();
            }, 'json');
        }
    }

    function scrollToBottom() {
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function escapeHtml(text) {
        if (!text) return text;
        return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    document.getElementById('chat-message').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendChat();
        }
    });

    if (currentThreadId > 0) {
        loadThread(currentThreadId, currentThreadCreator, currentThreadStatus);
    }
</script>

</body>
</html>