<?php
require_once("parent.php");

class Dosen extends classParent {
    public function __construct(){
        parent::__construct();
    }

    public function getDosen($npk = null) {
        if ($npk) {
            $sql = "SELECT * FROM dosen WHERE npk=?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $npk);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc(); 
            $stmt->close();
            return $data;
        } else {
            $sql = "SELECT * FROM dosen";
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

    public function insertDosen($arr_data) {
        $query = "INSERT INTO dosen (npk, nama, foto_extension) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sss", $arr_data['npk'], $arr_data['nama'], $arr_data['foto_extension']);

        return $stmt->execute();
    } 

    public function updateDosen($npk, $arr_data) {   
        $query = "UPDATE dosen SET nama = ?, foto_extension = ? WHERE npk = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sss", $arr_data['nama'], $arr_data['foto_extension'], $npk);

        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function deleteDosen($npk) {
        $query = "DELETE FROM dosen WHERE npk = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $npk);

        return $stmt->execute();
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo "<p style='color:green;'>Proses Berhasil!</p>";
    } elseif ($_GET['status'] == 'error') {
        echo "<p style='color:red;'>Proses Gagal.</p>";
    }
}
?>
