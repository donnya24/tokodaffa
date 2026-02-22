<?php
// api/products.php
require_once __DIR__ . '/../singnduetoko/config.php';
require_once __DIR__ . '/../singnduetoko/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Set timeout lebih pendek
set_time_limit(30);

try {
    $db = Database::getInstance();
    
    // Tambahkan limit untuk mencegah overload
    $products = $db->getProducts();
    
    // Jika tidak ada produk, kirim array kosong
    if (empty($products)) {
        echo json_encode([]);
        exit;
    }
    
    // Transform data untuk frontend
    $formatted = array_map(function($product) {
        return [
            'id' => (int)$product['id'],
            'nama' => htmlspecialchars($product['name'] ?? ''),
            'harga' => htmlspecialchars($product['price'] ?? ''),
            'satuan' => htmlspecialchars($product['unit'] ?? ''),
            'deskripsi' => htmlspecialchars($product['description'] ?? ''),
            'gambar' => $product['image'] ?? '',
            'isHighlight' => ($product['is_highlight'] ?? 'reguler') === 'unggulan'
        ];
    }, $products);
    
    echo json_encode($formatted);
    
} catch (Exception $e) {
    // Jangan kirim error detail ke client
    http_response_code(500);
    echo json_encode(['error' => 'Gagal memuat data']);
}