<?php
require_once("parent.php");

class MemberGrup extends classParent {
    public function __construct(){
        parent::__construct();
    }

    // Ambil member berdasarkan idgrup
    public function getMembersByGroup($idgrup) {
        $sql = "SELECT mg.username, a.nrp_mahasiswa, a.npk_dosen, m.nama as nama_mahasiswa, d.nama as nama_dosen
                FROM member_grup mg
                LEFT JOIN akun a ON mg.username = a.username
                LEFT JOIN mahasiswa m ON a.nrp_mahasiswa = m.nrp
                LEFT JOIN dosen d ON a.npk_dosen = d.npk
                WHERE mg.idgrup = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idgrup);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    // Cek apakah user sudah member
    public function isMember($idgrup, $username) {
        $sql = "SELECT * FROM member_grup WHERE idgrup = ? AND username = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $idgrup, $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $exists = $res->fetch_assoc();
        $stmt->close();
        return $exists ? true : false;
    }

    // Tambah member
    public function addMember($idgrup, $username) {
        $sql = "INSERT INTO member_grup (idgrup, username) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $idgrup, $username);
        return $stmt->execute();
    }

    // Hapus member
    public function deleteMember($idgrup, $username) {
        $sql = "DELETE FROM member_grup WHERE idgrup = ? AND username = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $idgrup, $username);
        return $stmt->execute();
    }
}
?>
