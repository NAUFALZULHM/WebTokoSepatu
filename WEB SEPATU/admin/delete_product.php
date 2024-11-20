<?php
session_start();
include('../server/connection.php');

// Memeriksa apakah pengguna telah login sebagai admin
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) || $_SESSION['akses'] !== 'admin') {
    header('location: ../login.php');
    exit;
}

// Memeriksa apakah parameter product_id ada di URL
if (!isset($_GET['product_id'])) {
    header('location: admin.php');
    exit;
}

$product_id = $_GET['product_id'];

// Query untuk menghapus produk dari database
$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    header('location: admin.php');
    exit;
} else {
    echo "Gagal menghapus produk. Silakan coba lagi.";
}
