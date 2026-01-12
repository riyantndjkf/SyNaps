<?php
require_once "parent.php";

class Chat extends classParent {
    
    public function __construct() {
        parent::__construct();
    }

    public function sendChat($id_thread, $username_pengirim, $isi_pesan) {
        // Sesuaikan kolom: idthread, username_pembuat, isi
        $sql = "INSERT INTO chat (idthread, username_pembuat, isi, tanggal_pembuatan) VALUES (?, ?, ?, NOW())";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("iss", $id_thread, $username_pengirim, $isi_pesan);
        
        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getChatsByThread($id_thread) {
        // Query Join Kompleks untuk mendapatkan Nama Asli (Mahasiswa/Dosen)
        // COALESCE akan memilih nama yang tidak NULL (Prioritas: Mhs -> Dosen -> Username) (soalnya pak henda ini aneh banget usernamenya jadi id hmm)
        $sql = "SELECT c.*, 
                       COALESCE(mhs.nama, dsn.nama, c.username_pembuat) as nama_pengirim 
                FROM chat c 
                JOIN akun a ON c.username_pembuat = a.username 
                LEFT JOIN mahasiswa mhs ON a.nrp_mahasiswa = mhs.nrp
                LEFT JOIN dosen dsn ON a.npk_dosen = dsn.npk
                WHERE c.idthread = ? 
                ORDER BY c.tanggal_pembuatan ASC";
        
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $id_thread);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
?>