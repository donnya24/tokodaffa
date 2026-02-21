<div class="w-64 bg-gradient-to-b from-green-900 to-green-800 text-white shadow-xl relative flex flex-col h-full">
    <!-- Header Sidebar -->
    <div class="p-6 border-b border-green-700 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="bg-green-600 p-2 rounded-xl">
                <i class="fas fa-store text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Toko Daffa</h3>
                <p class="text-xs text-green-300">Admin Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigasi - flex-1 agar mengambil sisa ruang -->
    <nav class="p-4 space-y-1 flex-1 overflow-y-auto">
        <a href="dashboard.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-green-700' : ''; ?>">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span>Dashboard</span>
        </a>
        <a href="products.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl <?php echo strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'bg-green-700' : ''; ?>">
            <i class="fas fa-box w-5"></i>
            <span>Produk</span>
        </a>
        <a href="hero.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl <?php echo basename($_SERVER['PHP_SELF']) == 'hero.php' ? 'bg-green-700' : ''; ?>">
            <i class="fas fa-image w-5"></i>
            <span>Hero Section</span>
        </a>
        <a href="tentang.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl <?php echo basename($_SERVER['PHP_SELF']) == 'tentang.php' ? 'bg-green-700' : ''; ?>">
            <i class="fas fa-info-circle w-5"></i>
            <span>Tentang Kami</span>
        </a>
        <a href="kontak.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl <?php echo basename($_SERVER['PHP_SELF']) == 'kontak.php' ? 'bg-green-700' : ''; ?>">
            <i class="fas fa-phone w-5"></i>
            <span>Kontak</span>
        </a>

        <div class="border-t border-green-700 my-4"></div>

        <!-- Button Logout -->
        <button onclick="openLogoutModal()" class="w-full flex items-center gap-3 py-3 px-4 hover:bg-red-700 rounded-xl text-red-200 transition">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span>Logout</span>
        </button>
    </nav>

    <!-- Info User -->
    <div class="p-4 border-t border-green-700">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-sm"></i>
            </div>
            <div class="text-xs">
                <p class="font-medium"><?php echo ADMIN_USERNAME; ?></p>
                <p class="text-green-300">Online</p>
            </div>
        </div>
    </div>

    <!-- FOOTER COPYRIGHT - di sidebar paling bawah -->
    <div class="p-3 text-center text-xs text-green-400 border-t border-green-800 bg-green-950 bg-opacity-30">
        © <?= date('Y'); ?> Toko Daffa. All rights reserved.
    </div>
</div>