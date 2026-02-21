<?php
// api/hero.php
require_once __DIR__ . '/../singnduetoko/config.php';
require_once __DIR__ . '/../singnduetoko/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $db = Database::getInstance();
    $hero = $db->getHeroSection();
    
    // Perbaiki format response agar sesuai dengan yang diharapkan komponen
    echo json_encode([
        'badge' => $hero['badge'] ?? 'Melayani perlengkapan sembako',
        'title1' => $hero['title1'] ?? 'Toko',
        'title2' => $hero['title2'] ?? 'Daffa',
        'subtitle' => $hero['subtitle'] ?? 'Segala kebutuhan sembako, bensin eceran + tabung gas LPG 3kg (melon). Harga ramah, pelayanan cepat.',
        'open_time' => $hero['open_time'] ?? '07:00',
        'close_time' => $hero['close_time'] ?? '21:30',
        'background_image' => $hero['background_image'] ?? 'toko-daffa.png',
        'button1_text' => $hero['button1_text'] ?? 'Lihat produk',
        'button1_link' => $hero['button1_link'] ?? '#produk',
        'button2_text' => $hero['button2_text'] ?? 'Kunjungi toko',
        'button2_link' => $hero['button2_link'] ?? '#kontak'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}