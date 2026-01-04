<?php
// Veritabaný Baðlantýsý
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dizi_blog');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Baðlantý hatasý: " . $conn->connect_error);
}

$conn->set_charset("utf8");
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_guest() {
    return !is_logged_in();
}

function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true;
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}
?>
