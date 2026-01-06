<?php
require_once "parent.php";

class Thread extends ParentClass {
    
    public function __construct($conn) {
        parent::__construct($conn);
    }

    public function createThread($id_grup, $username_pembuat) {
        $sql = "INSERT INTO thread (idgrup, username_pembuat, status, tanggal_pembuatan) VALUES (?, ?, 'Open', NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $id_grup, $username_pembuat);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        } else {
            return false;
        }
    }
    
    // 2. Mengambil Daftar Thread dalam Grup
    public function getThreads($id_grup) {
        // Query ini melakukan JOIN ke tabel akun, lalu ke mahasiswa/dosen 
        // untuk mengambil nama asli si pembuat thread.
        $sql = "SELECT t.*, 
                       COALESCE(mhs.nama, dsn.nama, t.username_pembuat) as nama_pembuat 
                FROM thread t 
                JOIN akun a ON t.username_pembuat = a.username 
                LEFT JOIN mahasiswa mhs ON a.nrp_mahasiswa = mhs.nrp
                LEFT JOIN dosen dsn ON a.npk_dosen = dsn.npk
                WHERE t.idgrup = ? 
                ORDER BY t.tanggal_pembuatan DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_grup);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getThreadById($id_thread) {
        // Sesuaikan 'id' menjadi 'idthread'
        $sql = "SELECT * FROM thread WHERE idthread = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_thread);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // 4. Menutup Thread (Close) - Hanya Pembuat yang bisa
    public function closeThread($id_thread, $username_login) {
        // Cek dulu apakah yang mau nutup adalah pemilik thread
        // Sesuaikan 'id_pembuat' jadi 'username_pembuat'
        $cek = "SELECT username_pembuat FROM thread WHERE idthread = ?";
        $stmt_cek = $this->db->prepare($cek);
        $stmt_cek->bind_param("i", $id_thread);
        $stmt_cek->execute();
        $res = $stmt_cek->get_result()->fetch_assoc();

        // Validasi kepemilikan
        if ($res && $res['username_pembuat'] == $username_login) {
            $sql = "UPDATE thread SET status = 'Close' WHERE idthread = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id_thread);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}
?>