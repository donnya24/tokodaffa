<?php
// src/components/produk.php
require_once __DIR__ . '/../../singnduetoko/config.php';
require_once __DIR__ . '/../../singnduetoko/database.php';

$db = Database::getInstance();
$products = $db->getProducts();
?>

<section id="produk" class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="inline-block bg-green-900 text-green-100 px-5 py-1.5 rounded-full text-sm font-semibold mb-3 border border-green-700">
                <i class="fas fa-tags mr-1"></i> Koleksi Terbaru
            </span>
            <h2 class="text-4xl font-bold text-gray-800">Produk Pilihan</h2>
            <p class="text-gray-600 mt-2 max-w-2xl mx-auto">
                Aneka sembako dan tabung gas LPG 3kg selalu tersedia
            </p>
        </div>

        <!-- Products Grid -->
        <?php if (empty($products)): ?>
            <div class="text-center py-12">
                <div class="bg-white rounded-2xl shadow-md p-8 max-w-md mx-auto">
                    <i class="fas fa-box-open text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Produk</h3>
                    <p class="text-gray-600">Saat ini belum ada produk yang tersedia.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($products as $product): ?>
                    <?php 
                    // Cek apakah produk unggulan (nilai 'unggulan')
                    $is_highlight = ($product['is_highlight'] == 'unggulan'); 
                    ?>
                    <div class="product-card rounded-2xl shadow-md hover:shadow-xl p-5 <?php echo $is_highlight ? 'border-2 border-green-700 ring-2 ring-green-400' : ''; ?>">
                        
                        <!-- Image Container -->
                        <div class="h-44 w-full rounded-xl overflow-hidden bg-gray-200 relative">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo getProductImageUrl($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     class="product-image w-full h-full object-cover"
                                     onerror="this.src='<?php echo getProductImageUrl('placeholder.png'); ?>'">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Highlight Badge - hanya tampil jika unggulan -->
                            <?php if ($is_highlight): ?>
                                <div class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1">
                                    <i class="fas fa-star text-xs"></i>
                                    <span>Unggulan</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Info -->
                        <h3 class="font-bold text-lg mt-4 line-clamp-2 <?php echo $is_highlight ? 'text-green-800' : 'text-gray-800'; ?>">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h3>
                        
                        <p class="text-green-700 text-sm"><?php echo htmlspecialchars($product['unit']); ?></p>
                        
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-xl font-semibold <?php echo $is_highlight ? 'text-green-800' : 'text-gray-900'; ?>">
                                <?php echo htmlspecialchars($product['price']); ?>
                            </span>
                        </div>

                        <!-- Deskripsi jika ada -->
                        <?php if (!empty($product['description'])): ?>
                            <p class="text-xs text-gray-600 mt-2 line-clamp-2">
                                <?php echo htmlspecialchars($product['description']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Info LPG -->
        <div class="mt-10 bg-black p-5 rounded-2xl flex flex-wrap items-center justify-between border border-green-800">
            <div class="flex items-center gap-2 text-green-300">
                <i class="fas fa-gas-pump text-xl"></i>
                <span class="font-medium">Bensin eceran + Tabung Lpg 3kg tersedia setiap hari · melayani tukar tabung</span>
            </div>
            <span class="bg-green-900 px-4 py-2 rounded-full text-green-200 text-sm border border-green-700">
                <i class="fas fa-check-circle mr-1"></i> Gas 3kg ready
            </span>
        </div>
    </div>
</section>

<style>
.product-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(21, 128, 61, 0.1);
    background: #ffffff;
}
.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 128, 0, 0.2),
                0 8px 10px -6px rgba(0, 100, 0, 0.1);
    border-color: #16a34a;
}
.product-image {
    transition: transform 0.5s ease;
}
.product-card:hover .product-image {
    transform: scale(1.1);
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>