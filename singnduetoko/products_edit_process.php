<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = 'Method not allowed';
    header('Location: products.php');
    exit();
}

// CSRF Protection
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = "Invalid CSRF token";
    header('Location: products.php');
    exit();
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if (!$id) {
    $_SESSION['error_message'] = 'ID produk tidak valid';
    header('Location: products.php');
    exit();
}

// Ambil data produk lama
$product = $db->getProduct($id);

if (!$product) {
    $_SESSION['error_message'] = 'Produk tidak ditemukan';
    header('Location: products.php');
    exit();
}

$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '');
$unit = trim($_POST['unit'] ?? '');
$description = trim($_POST['description'] ?? '');

// Handle is_highlight dengan ENUM (reguler/unggulan)
$is_highlight = 'reguler'; // Default

if (isset($_POST['is_highlight'])) {
    $highlight_value = $_POST['is_highlight'];
    
    // Log untuk debugging
    error_log("=== DEBUG IS_HIGHLIGHT EDIT ===");
    error_log("Raw value: " . $highlight_value);
    
    // Konversi ke string 'reguler' atau 'unggulan'
    if ($highlight_value === '1' || $highlight_value === 1 || $highlight_value === 'unggulan') {
        $is_highlight = 'unggulan';
        error_log("Result: UNGGULAN");
    } else {
        $is_highlight = 'reguler';
        error_log("Result: REGULER");
    }
} else {
    $is_highlight = 'reguler';
    error_log("No is_highlight in POST, default REGULER");
}

error_log("Final is_highlight: " . $is_highlight);

// Validasi
$errors = [];
if (empty($name)) $errors[] = 'Nama produk harus diisi';
if (empty($price)) $errors[] = 'Harga harus diisi';
if (empty($unit)) $errors[] = 'Satuan harus diisi';

$image_filename = $product['image'];
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $upload_result = uploadProductImage($_FILES['image'], $product['image']);
    if ($upload_result['success']) {
        $image_filename = $upload_result['filename'];
        error_log("Gambar baru diupload: " . $image_filename);
    } else {
        $errors[] = $upload_result['message'];
        error_log("Gagal upload gambar: " . $upload_result['message']);
    }
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode("<br>", $errors);
    header('Location: products_edit.php?id=' . $id);
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

error_log("Data update untuk ID $id: " . json_encode($data));

try {
    if ($db->updateProduct($id, $data)) {
        error_log("Produk ID $id berhasil diupdate");
        $_SESSION['success_message'] = 'Produk berhasil diperbarui!';
        header('Location: products.php');
        exit();
    } else {
        error_log("Gagal mengupdate produk ID $id - updateProduct mengembalikan false");
        $_SESSION['error_message'] = 'Gagal mengupdate database';
        header('Location: products_edit.php?id=' . $id);
        exit();
    }
} catch (Exception $e) {
    error_log("Exception saat update: " . $e->getMessage());
    $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
    header('Location: products_edit.php?id=' . $id);
    exit();
}