<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$page_title = "Dashboard";

$db = Database::getInstance();
$products = $db->getProducts();
$product_count = count($products);

include 'partials/header.php';
include 'partials/sidebar.php';
include 'partials/logout_modal.php';
?>

<!-- Content Dashboard -->
<div class="flex-1 overflow-y-auto">
    <!-- Content -->
    <div class="p-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-2xl shadow-lg p-6 text-white mb-8">
            <h3 class="text-xl font-bold mb-2">Selamat datang, Admin!</h3>
            <p class="text-green-100">Kelola konten website Toko Daffa dengan mudah melalui panel ini.</p>
        </div>
        
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $product_count; ?></p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i> Aktif
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="fas fa-box text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Hero Section</p>
                        <p class="text-3xl font-bold text-gray-800">Active</p>
                        <p class="text-xs text-blue-600 mt-2">
                            <i class="fas fa-check-circle mr-1"></i> Terkonfigurasi
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="fas fa-image text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Kontak</p>
                        <p class="text-3xl font-bold text-gray-800">Terisi</p>
                        <p class="text-xs text-yellow-600 mt-2">
                            <i class="fas fa-phone mr-1"></i> Siap digunakan
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-xl">
                        <i class="fas fa-phone-alt text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Menu Navigasi -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-compass text-green-600"></i>
                    Navigasi Cepat
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="products.php" class="bg-green-50 hover:bg-green-100 p-4 rounded-xl text-center transition">
                        <i class="fas fa-plus-circle text-2xl text-green-600 mb-2"></i>
                        <p class="text-sm font-medium">Tambah Produk</p>
                    </a>
                    <a href="hero.php" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-xl text-center transition">
                        <i class="fas fa-edit text-2xl text-blue-600 mb-2"></i>
                        <p class="text-sm font-medium">Edit Hero</p>
                    </a>
                    <a href="tentang.php" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-xl text-center transition">
                        <i class="fas fa-info-circle text-2xl text-purple-600 mb-2"></i>
                        <p class="text-sm font-medium">Edit Tentang</p>
                    </a>
                    <a href="kontak.php" class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-xl text-center transition">
                        <i class="fas fa-phone text-2xl text-yellow-600 mb-2"></i>
                        <p class="text-sm font-medium">Edit Kontak</p>
                    </a>
                </div>
            </div>
            
            <!-- Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-green-600"></i>
                    Informasi
                </h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>Semua perubahan tersimpan di database</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>Gambar produk maksimal 5MB (JPG, PNG, WEBP)</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span>Pastikan koneksi internet stabil saat upload</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>