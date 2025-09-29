<?php
require_once("parent.php");

class Mahasiswa extends classParent {
    public function delete($nrp) {
        // ambil NRP untuk hapus foto
        $stmt = $this->mysqli->prepare("SELECT nrp FROM mahasiswa WHERE nrp=?");
        $stmt->bind_param("s", $nrp);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($res && $res['nrp']) {
            $fotoFile = "images/" . $res['nrp'] . ".jpg";
            if (file_exists($fotoFile)) unlink($fotoFile);
        }

        // hapus data mahasiswa
        $stmt = $this->mysqli->prepare("DELETE FROM mahasiswa WHERE nrp=?");
        $stmt->bind_param("s", $nrp);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}

// hasil
if (isset($_GET["nrp"])) {
    $mhs = new Mahasiswa();
    if ($mhs->delete($_GET["nrp"])) {
        echo "Data mahasiswa berhasil dihapus";
    } else {
        echo "Gagal menghapus data mahasiswa";
    }
}
?>