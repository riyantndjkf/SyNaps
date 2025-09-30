<?php
require_once("parent.php");

class Dosen extends classParent {
    public function __construct(){
        parent::__construct();
    }

    public function getDosen($npk = null) {
        if ($npk) {
            // kalau ada parameter NPK, ambil hanya satu dosen
            $sql = "SELECT * FROM dosen WHERE npk=?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $npk);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc(); // satu baris saja
            $stmt->close();
            return $data;
        } else {
            // kalau tidak ada parameter, ambil semua dosen
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

        $stmt->execute();
        
        return $stmt->insert_id;

        $stmt->close();
    } 

    public function updateDosen($npk, $arr_data) {
            
        $query = "UPDATE dosen SET nama = ?, foto_extension = ? WHERE npk = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("sss", $arr_data['nama'], $arr_data['foto_extension'], $npk);

        $stmt->execute();
        
        return $stmt->affected_rows;

        $stmt->close();
    }

    public function deleteDosen($npk) {
        $query = "DELETE FROM dosen WHERE npk = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $npk);

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
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
