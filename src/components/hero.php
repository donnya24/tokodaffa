<?php
// src/components/hero.php
require_once __DIR__ . '/../../singnduetoko/config.php';
require_once __DIR__ . '/../../singnduetoko/database.php';

$db = Database::getInstance();
$hero = $db->getHeroSection();
?>

<section id="beranda" class="relative w-full h-[600px] md:h-[650px] overflow-hidden">
    
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full">
        <img src="src/assets/images/<?php echo htmlspecialchars($hero['background_image'] ?? 'toko-daffa.png'); ?>" 
             alt="Toko Daffa"
             class="w-full h-full object-cover object-center"
             onerror="this.src='src/assets/images/toko-daffa.png'">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40"></div>
    </div>

    <!-- Content -->
    <div class="absolute inset-0 flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl text-white">
                <!-- Badge -->
                <div class="inline-block bg-green-800/70 text-green-100 px-4 py-1.5 rounded-full text-sm font-semibold mb-4 border border-green-600 backdrop-blur-sm">
                    <i class="fas fa-fire mr-1 text-green-300"></i>
                    <span><?php echo htmlspecialchars($hero['badge'] ?? 'Melayani perlengkapan sembako'); ?></span>
                </div>
                
                <!-- Title -->
                <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white leading-tight">
                    <span><?php echo htmlspecialchars($hero['title1'] ?? 'Toko'); ?></span> 
                    <span class="text-green-300"><?php echo htmlspecialchars($hero['title2'] ?? 'Daffa'); ?></span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-xl md:text-2xl text-green-100 mt-4 max-w-lg">
                    <?php echo htmlspecialchars($hero['subtitle'] ?? 'Segala kebutuhan sembako, bensin eceran + tabung gas LPG 3kg (melon). Harga ramah, pelayanan cepat.'); ?>
                </p>
                
                <!-- Buttons -->
                <div class="flex flex-wrap gap-4 mt-8">
                    <a href="<?php echo htmlspecialchars($hero['button1_link'] ?? '#produk'); ?>"
                       class="bg-green-700 hover:bg-green-800 text-white px-7 py-3.5 rounded-full shadow-lg font-semibold transition flex items-center gap-2">
                        <i class="fas fa-basket-shopping"></i>
                        <span><?php echo htmlspecialchars($hero['button1_text'] ?? 'Lihat produk'); ?></span>
                    </a>
                    <a href="<?php echo htmlspecialchars($hero['button2_link'] ?? '#kontak'); ?>"
                       class="bg-black/50 backdrop-blur-sm border-2 border-white text-white hover:bg-black/70 px-7 py-3.5 rounded-full shadow-md font-semibold transition flex items-center gap-2">
                        <i class="fas fa-location-dot"></i>
                        <span><?php echo htmlspecialchars($hero['button2_text'] ?? 'Kunjungi toko'); ?></span>
                    </a>
                </div>
                
                <!-- Operating Hours -->
                <div class="mt-8 flex items-center text-green-100 bg-black/40 backdrop-blur-sm p-3 rounded-full w-fit gap-3 px-5 border border-green-700">
                    <i class="far fa-clock text-green-300"></i>
                    <span class="font-medium">
                        Buka <?php echo htmlspecialchars($hero['open_time'] ?? '07:00'); ?> - <?php echo htmlspecialchars($hero['close_time'] ?? '21:30'); ?> WIB
                    </span>
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                </div>
            </div>
        </div>
    </div>
</section>