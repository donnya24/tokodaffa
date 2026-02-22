<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$db = Database::getInstance();

// CSRF Protection
if (!isset($_GET['csrf_token']) || !verifyCSRFToken($_GET['csrf_token'])) {
    $_SESSION['error_message'] = "Invalid CSRF token";
    header('Location: products.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['error_message'] = 'ID produk tidak valid';
    header('Location: products.php');
    exit();
}

// Ambil data produk untuk mendapatkan nama gambar
$product = $db->getProduct($id);

if (!$product) {
    $_SESSION['error_message'] = 'Produk tidak ditemukan';
    header('Location: products.php');
    exit();
}

// Hapus file gambar jika ada (dan bukan placeholder)
if (!empty($product['image']) && $product['image'] !== 'placeholder.png') {
    $deleted = deleteProductImage($product['image']);
    if ($deleted) {
        error_log("Gambar berhasil dihapus: " . $product['image']);
    } else {
        error_log("Gagal menghapus gambar: " . $product['image'] . " - file mungkin tidak ada");
        // Tetap lanjutkan proses hapus dari database meskipun gambar gagal dihapus
    }
}

// Hapus dari database
try {
    $result = $db->deleteProduct($id);
    
    if ($result) {
        $_SESSION['success_message'] = 'Produk berhasil dihapus!';
        error_log("Produk ID $id berhasil dihapus dari database");
    } else {
        $_SESSION['error_message'] = 'Gagal menghapus produk dari database';
        error_log("Gagal menghapus produk ID $id dari database");
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
    error_log("Error hapus produk: " . $e->getMessage());
}

// Redirect kembali ke halaman products
header('Location: products.php');
exit();
?>