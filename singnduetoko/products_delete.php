<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

header('Content-Type: application/json');

$db = Database::getInstance();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Method not allowed';
    echo json_encode($response);
    exit();
}

// CSRF Protection
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    $response['message'] = "Invalid CSRF token";
    echo json_encode($response);
    exit();
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if (!$id) {
    $response['message'] = 'ID produk tidak valid';
    echo json_encode($response);
    exit();
}

// Ambil data produk untuk mendapatkan nama file gambar
$product = $db->getProduct($id);

if (!$product) {
    $response['message'] = 'Produk tidak ditemukan';
    echo json_encode($response);
    exit();
}

// Hapus gambar
if (!empty($product['image'])) {
    deleteProductImage($product['image']);
}

// Hapus dari database
if ($db->deleteProduct($id)) {
    $response['success'] = true;
    $response['message'] = 'Produk "' . htmlspecialchars($product['name']) . '" berhasil dihapus!';
} else {
    $response['message'] = 'Gagal menghapus produk';
}

echo json_encode($response);
exit();