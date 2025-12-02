<?php
require_once("parent.php");

class Grup extends classParent {
    public function __construct(){
        parent::__construct();
    }

    public function getGrup($idgrup = null) {
        if ($idgrup) {
            $sql = "SELECT * FROM grup WHERE idgrup = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $idgrup);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc();
            $stmt->close();
            return $data;
        } else {
            $sql = "SELECT * FROM grup";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = [];
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
            $stmt->close();
            return $data;
        }
    }

    public function getGrupByCreator($username) {
        $sql = "SELECT * FROM grup WHERE username_pembuat = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    public function getGrupByMember($username) {
    $sql = "SELECT g.* 
            FROM grup g
            JOIN member_grup mg ON g.idgrup = mg.idgrup
            WHERE mg.username = ?";
    $stmt = $this->mysqli->prepare($sql);
    if (!$stmt) return [];

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    return $data;
    }

    public function insertGrup($arr_data) {
        $query = "INSERT INTO grup (username_pembuat, nama, deskripsi, tanggal_pembentukan, jenis, kode_pendaftaran) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param(
            "ssssss",
            $arr_data['username_pembuat'],
            $arr_data['nama'],
            $arr_data['deskripsi'],
            $arr_data['tanggal_pembentukan'],
            $arr_data['jenis'],
            $arr_data['kode_pendaftaran']
        );
        return $stmt->execute();
    }

    public function updateGrup($idgrup, $arr_data) {
        $query = "UPDATE grup SET nama = ?, deskripsi = ?, jenis = ? WHERE idgrup = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sssi", $arr_data['nama'], $arr_data['deskripsi'], $arr_data['jenis'], $idgrup);
        return $stmt->execute();
    }

    public function deleteGrup($idgrup) {
        $query = "DELETE FROM grup WHERE idgrup = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $idgrup);
        return $stmt->execute();
    }

    public function getAvailablePublicGroups($username) {
        $sql = "SELECT * FROM grup 
                WHERE jenis = 'Publik' 
                AND idgrup NOT IN (SELECT idgrup FROM member_grup WHERE username = ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    public function getGrupByCode($kode) {
        $sql = "SELECT * FROM grup WHERE kode_pendaftaran = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $stmt->close();
        return $data;
    }
}
?>
