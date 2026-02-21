<?php
// src/components/kontak.php
require_once __DIR__ . '/../../singnduetoko/config.php';
require_once __DIR__ . '/../../singnduetoko/database.php';

$db = Database::getInstance();
$contact = $db->getContactSection();
?>

<section id="kontak" class="bg-gradient-to-br from-green-900 to-green-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold">Hubungi atau Kunjungi</h2>
            <p class="text-green-200 mt-2">kami siap melayani dengan sepenuh hati</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left: Address & Maps -->
            <div class="bg-black/40 p-8 rounded-3xl backdrop-blur-sm border border-green-700">
                <h3 class="text-2xl font-semibold flex items-center gap-2 mb-4">
                    <i class="fas fa-map-pin text-green-400"></i> Alamat
                </h3>
                <p class="text-green-100 mb-4"><?php echo nl2br(htmlspecialchars($contact['address'] ?? 'Jl. Ke Ngluyu, Gondang Kulon, Kec. Gondang, Kabupaten Nganjuk, Jawa Timur 64451')); ?></p>
                
                <!-- Google Maps Embed -->
                <div class="rounded-2xl overflow-hidden border-2 border-green-700 h-64 w-full">
                    <?php if (!empty($contact['maps_embed_url'])): ?>
                        <iframe src="<?php echo htmlspecialchars($contact['maps_embed_url']); ?>" 
                                style="border: 0"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="w-full h-full object-cover"></iframe>
                    <?php else: ?>
                        <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                            <p class="text-green-300">Maps tidak tersedia</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Maps Link -->
                <div class="mt-4 text-center">
                    <a href="<?php echo htmlspecialchars($contact['maps_link'] ?? '#'); ?>" 
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-full text-sm transition-colors">
                        <i class="fab fa-google"></i>
                        Buka di Google Maps
                    </a>
                </div>
            </div>

            <!-- Right: Contact -->
            <div class="bg-black/40 p-8 rounded-3xl backdrop-blur-sm border border-green-700">
                <h3 class="text-2xl font-semibold flex items-center gap-2 mb-6">
                    <i class="fas fa-phone-alt text-green-400"></i> Kontak
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-800 rounded-full flex items-center justify-center">
                            <i class="fab fa-whatsapp text-green-300"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-300">WhatsApp</p>
                            <p class="font-medium"><?php echo htmlspecialchars($contact['whatsapp_display'] ?? '0822-6462-8643'); ?></p>
                        </div>
                        <button onclick="copyWA('<?php echo htmlspecialchars($contact['whatsapp_number'] ?? '6282264628643'); ?>')" 
                                class="ml-auto bg-green-800 hover:bg-green-700 text-white px-3 py-1 rounded-full text-xs transition">
                            Salin
                        </button>
                    </div>
                </div>
                
                <!-- WA Button -->
                <div class="mt-8">
                    <a href="https://wa.me/<?php echo htmlspecialchars($contact['whatsapp_number'] ?? '6282264628643'); ?>?text=Halo%20Toko%20Daffa%2C%20saya%20mau%20order"
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full font-semibold inline-flex items-center gap-2 shadow-lg transition w-full justify-center">
                        <i class="fab fa-whatsapp text-xl"></i>
                        Chat via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyWA(number) {
        navigator.clipboard.writeText(number);
        alert('Nomor WhatsApp berhasil disalin!');
    }
    </script>
</section>