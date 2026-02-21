<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

header('Content-Type: application/json');

$db = Database::getInstance();
$response = ['success' => false, 'message' => ''];

// Debug mode
$debug = [];

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

// Ambil data produk lama
$product = $db->getProduct($id);

if (!$product) {
    $response['message'] = 'Produk tidak ditemukan';
    echo json_encode($response);
    exit();
}

$debug['old_product'] = $product;

$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? '';
$unit = $_POST['unit'] ?? '';
$description = $_POST['description'] ?? '';
$is_highlight = isset($_POST['is_highlight']) ? true : false;

$debug['new_data'] = [
    'name' => $name,
    'price' => $price,
    'unit' => $unit,
    'description' => $description,
    'is_highlight' => $is_highlight
];

// Validasi
$errors = [];
if (empty($name)) $errors[] = 'Nama produk harus diisi';
if (empty($price)) $errors[] = 'Harga harus diisi';
if (empty($unit)) $errors[] = 'Satuan harus diisi';

$image_filename = $product['image'];
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $debug['file_info'] = $_FILES['image'];
    
    $upload_result = uploadProductImage($_FILES['image'], $product['image']);
    if ($upload_result['success']) {
        $image_filename = $upload_result['filename'];
        $debug['upload_success'] = $image_filename;
    } else {
        $errors[] = $upload_result['message'];
        $debug['upload_error'] = $upload_result['message'];
    }
}

if (!empty($errors)) {
    $response['message'] = implode("\n", $errors);
    $response['debug'] = $debug;
    echo json_encode($response);
    exit();
}

$data = [
    'name' => $name,
    'price' => $price,
    'unit' => $unit,
    'description' => $description,
    'image' => $image_filename,
    'is_highlight' => $is_highlight
];

try {
    if ($db->updateProduct($id, $data)) {
        $response['success'] = true;
        $response['message'] = 'Produk berhasil diupdate!';
    } else {
        $response['message'] = 'Gagal mengupdate database';
    }
} catch (Exception $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    $response['debug'] = $debug;
}

echo json_encode($response);
exit();