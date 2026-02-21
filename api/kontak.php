<?php
// api/kontak.php
require_once __DIR__ . '/../singnduetoko/config.php';
require_once __DIR__ . '/../singnduetoko/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $db = Database::getInstance();
    $contact = $db->getContactSection();
    
    // Pastikan format response konsisten
    echo json_encode([
        'address' => $contact['address'] ?? 'Jl. Ke Ngluyu, Gondang Kulon, Kec. Gondang, Kabupaten Nganjuk, Jawa Timur 64451',
        'whatsapp_number' => $contact['whatsapp_number'] ?? '6282264628643',
        'whatsapp_display' => $contact['whatsapp_display'] ?? '0822-6462-8643',
        'maps_embed_url' => $contact['maps_embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.4883479273976!2d111.95391829219946!3d-7.52159063685561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7837faa376c42f%3A0xd992eb38538977cc!2sTOKO%20%22DAFFA%22!5e0!3m2!1sid!2sid!4v1771508713569!5m2!1sid!2sid',
        'maps_link' => $contact['maps_link'] ?? 'https://maps.app.goo.gl/w7oNywaz7Huiu6pVA'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}