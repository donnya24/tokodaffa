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

// Ambil data form
$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '');
$unit = trim($_POST['unit'] ?? '');
$description = trim($_POST['description'] ?? '');

// Handle is_highlight dengan ENUM (reguler/unggulan)
$is_highlight = 'reguler'; // Default

if (isset($_POST['is_highlight'])) {
    $highlight_value = $_POST['is_highlight'];
    
    // Log untuk debugging
    error_log("=== DEBUG IS_HIGHLIGHT CREATE ===");
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

// Upload gambar
$image_filename = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $upload_result = uploadProductImage($_FILES['image']);
    if ($upload_result['success']) {
        $image_filename = $upload_result['filename'];
        error_log("Gambar berhasil diupload: " . $image_filename);
    } else {
        $errors[] = $upload_result['message'];
        error_log("Gagal upload gambar: " . $upload_result['message']);
    }
} else {
    $errors[] = 'Gambar produk harus diupload';
    error_log("Tidak ada file gambar diupload");
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode("<br>", $errors);
    header('Location: products_create.php');
    exit();
}

// Siapkan data untuk database
$data = [
    'name' => $name,
    'price' => $price,
    'unit' => $unit,
    'description' => $description,
    'image' => $image_filename,
    'is_highlight' => $is_highlight
];

error_log("Data yang akan disimpan: " . json_encode($data));

// Simpan ke database
try {
    $new_id = $db->createProduct($data);
    
    if ($new_id) {
        error_log("Produk berhasil disimpan dengan ID: " . $new_id);
        $_SESSION['success_message'] = 'Produk berhasil ditambahkan!';
        header('Location: products.php');
        exit();
    } else {
        error_log("Gagal menyimpan produk - createProduct mengembalikan false");
        $_SESSION['error_message'] = 'Gagal menyimpan ke database';
        header('Location: products_create.php');
        exit();
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
    header('Location: products_create.php');
    exit();
}