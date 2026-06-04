<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'includes/header.php';
include 'includes/sidebar.php';

// Simple Routing
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {
    case 'dashboard':
        include 'modules/dashboard.php';
        break;
    case 'kategori':
        include 'modules/kategori.php';
        break;
    case 'barang':
        include 'modules/barang.php';
        break;
    case 'users':
        include 'modules/users.php';
        break;
    case 'stok':
        include 'modules/stok.php';
        break;
    case 'laporan':
        include 'modules/laporan.php';
        break;
    default:
        include 'modules/dashboard.php';
        break;
}

include 'includes/footer.php';
?>
