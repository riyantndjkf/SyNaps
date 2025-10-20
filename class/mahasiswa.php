<?php
require_once("parent.php");

class Mahasiswa extends classParent {
    public function __construct(){
        parent::__construct();
    }

    public function getMahasiswa($nrp = null) {
        if ($nrp) {
            $sql = "SELECT * FROM mahasiswa WHERE nrp=?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $nrp);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc(); 
            $stmt->close();
            return $data;
        } else {
            $sql = "SELECT * FROM mahasiswa";
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

    public function insertMahasiswa($arr_data) {
        $query = "INSERT INTO mahasiswa (nrp, nama, gender, tanggal_lahir, angkatan, foto_extention) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param(
            "ssssis",$arr_data['nrp'], $arr_data['nama'], $arr_data['gender'], $arr_data['tanggal_lahir'], $arr_data['angkatan'], $arr_data['foto_extention']
        );
        return $stmt->execute();
    }

    public function updateMahasiswa($nrp, $arr_data) {
        $query = "UPDATE mahasiswa SET nama=?, gender=?, tanggal_lahir=?, angkatan=?, foto_extention=? WHERE nrp=?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sssiss", $arr_data['nama'], $arr_data['gender'], $arr_data['tanggal_lahir'], $arr_data['angkatan'], $arr_data['foto_extention'], $nrp);

        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function deleteMahasiswa($nrp) {
        $query = "DELETE FROM mahasiswa WHERE nrp=?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $nrp);

        return $stmt->execute();
    }
}

$mhsObj = new Mahasiswa();
$mahasiswas = $mhsObj->getMahasiswa();

?>

