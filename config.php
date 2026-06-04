<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_toko_bangunan_jwp";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Base URL configuration
$base_url = "http://localhost/jewepe/";

// Function for redirecting
function redirect($url) {
    echo "<script>window.location.href='$url';</script>";
    exit;
}

// Function for base_url
function base_url($path = "") {
    global $base_url;
    return $base_url . $path;
}
?>
