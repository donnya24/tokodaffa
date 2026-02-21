<?php
// api/products.php
require_once __DIR__ . '/../singnduetoko/config.php';
require_once __DIR__ . '/../singnduetoko/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $db = Database::getInstance();
    $products = $db->getProducts();
    
    // Transform data untuk frontend - pastikan format array
    $formatted = array_map(function($product) {
        return [
            'id' => $product['id'],
            'nama' => $product['name'],
            'harga' => $product['price'],
            'satuan' => $product['unit'],
            'deskripsi' => $product['description'] ?? '',
            'gambar' => $product['image'] ?? '',
            'isHighlight' => (bool)($product['is_highlight'] ?? false)
        ];
    }, $products);
    
    echo json_encode($formatted);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}