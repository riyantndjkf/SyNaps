<?php
require_once("../security.php");
require_once("../class/thread.php");
require_once("../class/koneksi.php");

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Ambil parameter
$id_thread = isset($_POST['idthread']) ? (int)$_POST['idthread'] : 0;

// Validasi parameter
if ($id_thread <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid thread ID']);
    exit;
}

try {
    $threadObj = new Thread();
    $username = $_SESSION['username'];
    
    // Cek apakah thread ada
    $thread = $threadObj->getThreadById($id_thread);
    
    if (!$thread) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Thread not found']);
        exit;
    }
    
    // Cek apakah user adalah pembuat thread
    if ($thread['username_pembuat'] !== $username) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Hanya pembuat thread yang bisa menutupnya']);
        exit;
    }
    
    // Cek apakah sudah closed
    if ($thread['status'] === 'Close') {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Thread sudah ditutup']);
        exit;
    }
    
    // Close thread
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
