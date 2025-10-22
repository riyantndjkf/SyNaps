<?php 
session_start(); 
require_once("class/akun.php"); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $username = $_POST['username']; 
    $password = $_POST['password']; 
    if (empty($username) || empty($password)) { 
        header("Location: login.php?status=empty");
        exit;
    } 
    $akun = new Akun(); 
    $row = $akun->login($username, $password);

    if ($row !== false) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['isadmin'] = $row['isadmin'];
        $_SESSION['nrp_mahasiswa'] = $row['nrp_mahasiswa'];
        $_SESSION['npk_dosen'] = $row['npk_dosen'];
        if (isset($_SESSION['last_page'])) { 
            $redirect_url = $_SESSION['last_page']; 
            unset($_SESSION['last_page']);
        } else { 
            $redirect_url = 'index.php'; 
        } 
        header("Location: " . $redirect_url); 
        exit; 
    } else { 
        header("Location: login.php?status=error"); 
        exit; 
    } 
} else {
    header("Location: login.php"); 
    exit; 
}
?>
