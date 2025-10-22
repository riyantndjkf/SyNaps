<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    $domain = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $url = "http://" . $domain . $uri;

    $_SESSION['last_page'] = $url;

    header("Location: login.php");
    exit;
}

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }

    return htmlentities($data);
}

$_GET     = sanitize($_GET);
$_POST    = sanitize($_POST);
$_REQUEST = sanitize($_REQUEST);


function validateNumber($value) {
    return is_numeric($value);
}
?>
