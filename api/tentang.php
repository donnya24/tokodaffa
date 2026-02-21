<?php
// api/tentang.php
require_once __DIR__ . '/../singnduetoko/config.php';
require_once __DIR__ . '/../singnduetoko/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $db = Database::getInstance();
    $about = $db->getAboutSection();
    
    echo json_encode([
        'title' => $about['title'] ?? 'Toko Daffa',
        'description' => $about['description'] ?? 'Sejak 2015 melayani kebutuhan sembako masyarakat. Toko Daffa dikenal dengan kelengkapan barang, harga bersahabat, dan pelayanan ramah.',
        'year_established' => $about['year_established'] ?? '2015',
        'customer_count' => $about['customer_count'] ?? '500+',
        'feature1' => $about['feature1'] ?? 'pelanggan tetap',
        'feature2' => $about['feature2'] ?? 'gratis antar',
        'feature2_note' => $about['feature2_note'] ?? 'hanya radius tertentu',
        'image' => $about['image'] ?? 'dalamtoko.png'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}