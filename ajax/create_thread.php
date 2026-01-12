<?php
require_once("../security.php");
require_once("../class/thread.php");
require_once("../class/grup.php");
require_once("../class/member_grup.php");
require_once("../class/koneksi.php");

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Ambil parameter
$id_grup = isset($_POST['idgrup']) ? (int)$_POST['idgrup'] : 0;

// Validasi parameter
if ($id_grup <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid grup ID']);
    exit;
}

try {
    // Cek apakah user adalah member atau pembuat grup
    $grupObj = new Grup();
    $grup = $grupObj->getGrup($id_grup);
    
    if (!$grup) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Grup not found']);
        exit;
    }
    
    $memberObj = new MemberGrup();
    $username = $_SESSION['username'];
    $isPembuat = ($grup['username_pembuat'] == $username);
    $isMember = $memberObj->isMember($id_grup, $username);
    
    if (!$isPembuat && !$isMember) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Anda bukan member grup ini']);
        exit;
    }
    
    // Buat thread baru
    $threadObj = new Thread();
    $new_thread_id = $threadObj->createThread($id_grup, $username);
    
    if ($new_thread_id) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Thread berhasil dibuat',
            'idthread' => $new_thread_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal membuat thread']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
