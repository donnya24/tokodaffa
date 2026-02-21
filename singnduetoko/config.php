<?php
// Pastikan TIDAK ADA spasi atau karakter sebelum <?php
session_start();

// ================= LOAD ENV =================
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// ================= APP CONFIG =================
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('SITE_URL', $_ENV['SITE_URL'] ?? 'http://localhost');

// ================= DATABASE CONFIG (POOLER) =================
define('DB_HOST', $_ENV['DB_HOST'] ?? '');
define('DB_PORT', $_ENV['DB_PORT'] ?? '6543');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'postgres');
define('DB_USER', $_ENV['DB_USER'] ?? '');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// ================= ADMIN CONFIG =================
define('ADMIN_USERNAME', $_ENV['ADMIN_USERNAME'] ?? '');
define('ADMIN_PASSWORD_HASH', $_ENV['ADMIN_PASSWORD_HASH'] ?? '');

// ================= PATH CONFIG =================
define('PRODUCTS_IMAGE_PATH', __DIR__ . '/../src/assets/images/products/');
define('PRODUCTS_IMAGE_URL', '/src/assets/images/products/');

define('MAX_FILE_SIZE', 5 * 1024 * 1024);

// ================= IMAGE CONFIG =================
$allowed_image_types = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp'
];

$allowed_extensions = [
    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp'
];

// ================= AUTH FUNCTIONS =================
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) 
        && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /singnduetoko/index.php');
        exit();
    }
}

// ================= CSRF =================
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) 
        && hash_equals($_SESSION['csrf_token'], $token);
}

// ================= IMAGE UPLOAD =================
function uploadProductImage($file, $old_image = null) {
    global $allowed_image_types, $allowed_extensions;

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File tidak valid'];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Ukuran maksimal 5MB'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);

    if (!in_array($mime_type, $allowed_image_types)) {
        return ['success' => false, 'message' => 'Format gambar tidak diizinkan'];
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed_extensions)) {
        return ['success' => false, 'message' => 'Ekstensi tidak diizinkan'];
    }

    // Generate nama file unik
    $new_filename = 'product_' . time() . '_' . uniqid() . '.' . $extension;
    $upload_path = PRODUCTS_IMAGE_PATH . $new_filename;

    // Buat folder jika belum ada
    if (!file_exists(PRODUCTS_IMAGE_PATH)) {
        mkdir(PRODUCTS_IMAGE_PATH, 0755, true);
    }

    // Hapus gambar lama jika ada (auto replace)
    if ($old_image && !empty($old_image)) {
        $old_file_path = PRODUCTS_IMAGE_PATH . $old_image;
        if (file_exists($old_file_path) && is_file($old_file_path)) {
            // Hanya hapus jika file benar-benar ada dan bukan default/placeholder
            if (strpos($old_image, 'product_') === 0 || strpos($old_image, 'produk-') === 0) {
                unlink($old_file_path);
                error_log("Gambar lama dihapus: " . $old_image);
            }
        }
    }

    // Upload file baru
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        error_log("Gambar baru diupload: " . $new_filename);
        return [
            'success' => true,
            'filename' => $new_filename
        ];
    }

    return ['success' => false, 'message' => 'Gagal upload file'];
}

function deleteProductImage($filename) {
    if (!$filename) return false;

    $filepath = PRODUCTS_IMAGE_PATH . $filename;

    if (file_exists($filepath) && strpos($filename, 'product_') === 0) {
        return unlink($filepath);
    }

    return false;
}

function getProductImageUrl($filename) {
    // Deteksi base URL secara otomatis
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    
    // Dapatkan folder root (misal: /tokodaffa)
    $root_folder = '';
    if (strpos($script_name, '/') !== false) {
        $parts = explode('/', trim($script_name, '/'));
        if (count($parts) > 0 && $parts[0] !== 'singnduetoko') {
            $root_folder = '/' . $parts[0];
        }
    }
    
    // Base URL
    $base_url = $protocol . $host . $root_folder;
    
    // Path gambar
    $image_path = '/src/assets/images/products/';
    
    if (empty($filename)) {
        return $base_url . $image_path . 'placeholder.png';
    }
    
    // Cek file di filesystem
    $file_path = $_SERVER['DOCUMENT_ROOT'] . $root_folder . $image_path . $filename;
    
    if (file_exists($file_path)) {
        return $base_url . $image_path . $filename;
    } else {
        error_log("Gambar tidak ditemukan: " . $file_path);
        return $base_url . $image_path . 'placeholder.png';
    }
}