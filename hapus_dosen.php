<?php
require_once("parent.php");

class Dosen extends classParent {
    public function delete($npk) {
        // ambil NPK untuk hapus foto
        $stmt = $this->mysqli->prepare("SELECT npk FROM dosen WHERE npk=?");
        $stmt->bind_param("s", $npk);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($res && $res['npk']) {
            $fotoFile = "images/" . $res['npk'] . ".jpg";
            if (file_exists($fotoFile)) unlink($fotoFile);
        }

        // hapus data dosen dari tabel
        $stmt = $this->mysqli->prepare("DELETE FROM dosen WHERE npk=?");
        $stmt->bind_param("s", $npk);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}

// hasil
if (isset($_GET["npk"])) {
    $dsn = new Dosen();
    if ($dsn->delete($_GET["npk"])) {
        echo "Data dosen berhasil dihapus";
    } else {
        echo "Gagal menghapus data dosen";
    }
}
?>