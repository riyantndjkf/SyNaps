<?php
require_once("../security.php");
require_once("../class/chat.php");
require_once("../class/thread.php");
require_once("../class/koneksi.php");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$id_thread = isset($_GET['idthread']) ? (int)$_GET['idthread'] : 0;
$last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

if ($id_thread <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid thread ID']);
    exit;
}

try {
    $threadObj = new Thread();
    $thread = $threadObj->getThreadById($id_thread);
    
    if (!$thread) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Thread not found']);
        exit;
    }
    
    $chatObj = new Chat();
    
    if ($last_id > 0) {
        $all_chats = $chatObj->getChatsByThread($id_thread);
        $chats = array_filter($all_chats, function($chat) use ($last_id) {
            return $chat['idchat'] > $last_id;
        });
        $chats = array_values($chats);
    } else {
        $chats = $chatObj->getChatsByThread($id_thread);
    }
    
    $response = [
        'status' => 'success',
        'chats' => $chats,
        'current_user' => $_SESSION['username'],
        'nama_user' => $_SESSION['nama_lengkap'] ?? $_SESSION['username']
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
