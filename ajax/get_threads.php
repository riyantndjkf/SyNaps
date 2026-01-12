<?php
require_once("../security.php");
require_once("../class/thread.php");
require_once("../class/koneksi.php");

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Ambil parameter
$id_grup = isset($_GET['idgrup']) ? (int)$_GET['idgrup'] : 0;

// Validasi parameter
if ($id_grup <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid grup ID']);
    exit;
}

try {
    $threadObj = new Thread();
    
    $threads = $threadObj->getThreads($id_grup);
    
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'threads' => $threads
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
