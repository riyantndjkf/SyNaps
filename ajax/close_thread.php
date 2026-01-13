<?php
require_once("../security.php");
require_once("../class/thread.php");
require_once("../class/koneksi.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$id_thread = isset($_POST['idthread']) ? (int)$_POST['idthread'] : 0;

if ($id_thread <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid thread ID']);
    exit;
}

try {
    $threadObj = new Thread();
    $username = $_SESSION['username'];
    $thread = $threadObj->getThreadById($id_thread);
    
    if (!$thread) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Thread not found']);
        exit;
    }
    
    if ($thread['username_pembuat'] !== $username) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Hanya pembuat thread yang bisa menutupnya']);
        exit;
    }

    if ($thread['status'] === 'Close') {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Thread sudah ditutup']);
        exit;
    }
    $result = $threadObj->closeThread($id_thread, $username);
    
    if ($result) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Thread berhasil ditutup'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal menutup thread']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
