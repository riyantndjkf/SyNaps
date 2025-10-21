<?php
require_once(__DIR__ . "/parent.php");

class Akun extends classParent
{
    public function __construct()
    {
        parent::__construct();
    }


    // Login akun
    public function login($username, $password)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM akun WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    // Insert akun untuk dosen
    public function insertAkunDosen($username, $password, $npk_dosen, $isadmin = 0)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO akun (username, password, npk_dosen, isadmin) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $password, $npk_dosen, $isadmin);
        return $stmt->execute();
    }

    // Insert akun untuk mahasiswa
    public function insertAkunMahasiswa($username, $password, $nrp_mahasiswa, $isadmin = 0)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO akun (username, password, nrp_mahasiswa, isadmin) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $password, $nrp_mahasiswa, $isadmin);
        return $stmt->execute();
    }

    public function deleteAkunDosen($npk)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM akun WHERE npk_dosen = ?");
        $stmt->bind_param("s", $npk);
        return $stmt->execute();
    }

    public function deleteAkunMahasiswa($nrp)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM akun WHERE nrp_mahasiswa = ?");
        $stmt->bind_param("s", $nrp);
        return $stmt->execute();
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
