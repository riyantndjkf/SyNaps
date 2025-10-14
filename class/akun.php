<?php
require_once(__DIR__ . "/parent.php");

class Akun extends classParent
{
    public function __construct()
    {
        parent::__construct();
    }

    // Ambil akun berdasarkan username
    public function getAkun($username)
    {
        $sql = "SELECT * FROM akun WHERE username = ?";
        $stmt = $this->mysqli->prepare($sql); // ✅ gunakan $this->mysqli, bukan $this->conn
        if (!$stmt) return false;

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: false;
    }

    // Registrasi akun baru
    public function register($username, $plain_pwd, $nama, $nrp = null, $npk = null, $isadmin = 0)
    {
        $hash = password_hash($plain_pwd, PASSWORD_DEFAULT);

        // Pastikan nilai kosong dianggap NULL
        if ($nrp === "" || $nrp === "0") $nrp = null;
        if ($npk === "" || $npk === "0") $npk = null;

        // === CEK & TAMBAH MAHASISWA OTOMATIS ===
        if (!empty($nrp)) {
            $cekMhs = $this->mysqli->prepare("SELECT nrp FROM mahasiswa WHERE nrp = ?");
            $cekMhs->bind_param("s", $nrp);
            $cekMhs->execute();
            $resMhs = $cekMhs->get_result();

            if ($resMhs->num_rows == 0) {
                $insertMhs = $this->mysqli->prepare("INSERT INTO mahasiswa (nrp, nama) VALUES (?, ?)");
                $insertMhs->bind_param("ss", $nrp, $nama);
                $insertMhs->execute();
                $insertMhs->close();
            }
            $cekMhs->close();
        }

        // === CEK & TAMBAH DOSEN OTOMATIS ===
        if (!empty($npk)) {
            $cekDsn = $this->mysqli->prepare("SELECT npk FROM dosen WHERE npk = ?");
            $cekDsn->bind_param("s", $npk);
            $cekDsn->execute();
            $resDsn = $cekDsn->get_result();

            if ($resDsn->num_rows == 0) {
                $insertDsn = $this->mysqli->prepare("INSERT INTO dosen (npk, nama) VALUES (?, ?)");
                $insertDsn->bind_param("ss", $npk, $nama);
                $insertDsn->execute();
                $insertDsn->close();
            }
            $cekDsn->close();
        }

        // === INSERT AKUN (tambahkan kolom nama) ===
        $sql = "INSERT INTO akun (username, password, nrp_mahasiswa, npk_dosen, isadmin)
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("sssii", $username, $hash, $nrp, $npk, $isadmin);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    // Login akun
    public function login($username, $plain_pwd)
    {
        $row = $this->getAkun($username);
        if (!$row) return false;

        return password_verify($plain_pwd, $row['password']) ? $row : false;
    }

    // Update password
    public function updatePassword($username, $new_plain_pwd) 
    {   
        $hash = password_hash($new_plain_pwd, PASSWORD_DEFAULT);
        $query = "UPDATE akun SET password = ? WHERE username = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ss", $hash, $username);

        $stmt->execute();
        return $stmt->affected_rows;
    }
}
?>
