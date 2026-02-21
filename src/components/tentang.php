<?php
// src/components/tentang.php
require_once __DIR__ . '/../../singnduetoko/config.php';
require_once __DIR__ . '/../../singnduetoko/database.php';

$db = Database::getInstance();
$about = $db->getAboutSection();
?>

<section id="tentang" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    
    <div class="flex flex-col md:flex-row gap-12 items-center">
        <!-- Image -->
        <div class="md:w-1/2 rounded-3xl overflow-hidden shadow-xl border-4 border-green-900 hover:shadow-2xl transition">
            <img src="src/assets/images/<?php echo htmlspecialchars($about['image'] ?? 'dalamtoko.png'); ?>" 
                 alt="Suasana dalam Toko Daffa"
                 class="w-full h-auto object-cover aspect-[4/3]"
                 onerror="this.src='src/assets/images/dalamtoko.png'">
        </div>
        
        <!-- Description -->
        <div class="md:w-1/2">
            <span class="text-green-700 font-semibold tracking-wide uppercase text-sm">
                <i class="fas fa-store-alt mr-2"></i>tentang kami
            </span>
            <h2 class="text-4xl font-bold text-gray-800 mt-2"><?php echo htmlspecialchars($about['title'] ?? 'Toko Daffa'); ?></h2>
            <p class="text-gray-600 mt-4 leading-relaxed"><?php echo nl2br(htmlspecialchars($about['description'] ?? 'Sejak 2015 melayani kebutuhan sembako masyarakat. Toko Daffa dikenal dengan kelengkapan barang, harga bersahabat, dan pelayanan ramah.')); ?></p>
            
            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="bg-white p-4 rounded-xl text-center shadow-md border border-gray-200">
                    <div class="text-3xl text-green-700 mb-2">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="font-bold text-gray-800"><?php echo htmlspecialchars($about['customer_count'] ?? '500+'); ?></span>
                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($about['feature1'] ?? 'pelanggan tetap'); ?></p>
                </div>
                <div class="bg-white p-4 rounded-xl text-center shadow-md border border-gray-200">
                    <div class="text-3xl text-green-700 mb-2">
                        <i class="fas fa-truck"></i>
                    </div>
                    <span class="font-bold text-gray-800"><?php echo htmlspecialchars($about['feature2'] ?? 'gratis antar'); ?></span>
                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($about['feature2_note'] ?? 'hanya radius tertentu'); ?></p>
                </div>
            </div>
            
            <p class="mt-6 flex items-center gap-2 text-gray-700">
                <i class="fas fa-store text-green-700"></i>
                Dikelola langsung oleh pemilik toko
            </p>
        </div>
    </div>
</section>