<?php
require_once("../security.php");
require_once("../class/chat.php");
require_once("../class/thread.php");
require_once("../class/koneksi.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Ambil parameter
$id_thread = isset($_POST['idthread']) ? (int)$_POST['idthread'] : 0;
$isi_pesan = isset($_POST['isi']) ? trim($_POST['isi']) : '';

// Validasi parameter
if ($id_thread <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid thread ID']);
    exit;
}

if (empty($isi_pesan)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Pesan tidak boleh kosong']);
    exit;
}

try {
    // Cek apakah thread masih open
    $threadObj = new Thread();
    $thread = $threadObj->getThreadById($id_thread);
    
    if (!$thread) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Thread not found']);
        exit;
    }
    
    if ($thread['status'] === 'Close') {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Thread sudah ditutup']);
        exit;
    }
    
    // Kirim chat
    $chatObj = new Chat();
    $username = $_SESSION['username'];
    
    $result = $chatObj->sendChat($id_thread, $username, $isi_pesan);
    
    if ($result) {
        // Refresh chat untuk dapat data terbaru
        $chats = $chatObj->getChatsByThread($id_thread);
        $new_chat = end($chats); // Ambil chat terakhir
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim',
            'chat' => $new_chat
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
