<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$db = Database::getInstance();
$heroData = $db->getHeroSection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token";
    } else {
        $data = [
            'badge' => $_POST['badge'],
            'title1' => $_POST['title1'],
            'title2' => $_POST['title2'],
            'subtitle' => $_POST['subtitle'],
            'open_time' => $_POST['open_time'],
            'close_time' => $_POST['close_time'],
            'background_image' => $_POST['background_image'],
            'button1_text' => $_POST['button1_text'],
            'button1_link' => $_POST['button1_link'],
            'button2_text' => $_POST['button2_text'],
            'button2_link' => $_POST['button2_link']
        ];
        
        if ($db->updateHeroSection($data)) {
            $success = "Hero section berhasil diupdate!";
            $heroData = $db->getHeroSection(); // Refresh data
        } else {
            $error = "Gagal mengupdate data";
        }
    }
}

$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section - Toko Daffa Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-green-900 to-green-800 text-white shadow-xl">
            <div class="p-6 border-b border-green-700">
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
            
            <nav class="p-4 space-y-1">
                <a href="dashboard.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl transition">
                    <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                </a>
                <a href="products.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl transition">
                    <i class="fas fa-box w-5"></i> Produk
                </a>
                <a href="hero.php" class="flex items-center gap-3 py-3 px-4 bg-green-700 rounded-xl">
                    <i class="fas fa-image w-5"></i> Hero Section
                </a>
                <a href="tentang.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl transition">
                    <i class="fas fa-store w-5"></i> Tentang Kami
                </a>
                <a href="kontak.php" class="flex items-center gap-3 py-3 px-4 hover:bg-green-700 rounded-xl transition">
                    <i class="fas fa-phone w-5"></i> Kontak
                </a>
                <a href="logout.php" class="flex items-center gap-3 py-3 px-4 hover:bg-red-700 rounded-xl transition text-red-200 mt-8">
                    <i class="fas fa-sign-out-alt w-5"></i> Logout
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white shadow-sm p-6 mb-6 sticky top-0 z-10">
                <h2 class="text-2xl font-bold text-gray-800">Edit Hero Section</h2>
            </div>
            
            <div class="p-6">
                <?php if (isset($success)): ?>
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span><?php echo $success; ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Badge Text</label>
                                <input type="text" name="badge" required
                                       value="<?php echo htmlspecialchars($heroData['badge']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Judul 1</label>
                                    <input type="text" name="title1" required
                                           value="<?php echo htmlspecialchars($heroData['title1']); ?>"
                                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Judul 2</label>
                                    <input type="text" name="title2" required
                                           value="<?php echo htmlspecialchars($heroData['title2']); ?>"
                                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-semibold mb-2">Subjudul</label>
                                <textarea name="subtitle" rows="3" required
                                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"><?php echo htmlspecialchars($heroData['subtitle']); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Jam Buka</label>
                                <input type="text" name="open_time" required
                                       value="<?php echo htmlspecialchars($heroData['open_time']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Jam Tutup</label>
                                <input type="text" name="close_time" required
                                       value="<?php echo htmlspecialchars($heroData['close_time']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Background Image</label>
                                <input type="text" name="background_image" required
                                       value="<?php echo htmlspecialchars($heroData['background_image']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                            </div>
                            
                            <div class="md:col-span-2">
                                <h3 class="font-bold text-lg mb-4">Tombol</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Tombol 1 Teks</label>
                                        <input type="text" name="button1_text" required
                                               value="<?php echo htmlspecialchars($heroData['button1_text']); ?>"
                                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Tombol 1 Link</label>
                                        <input type="text" name="button1_link" required
                                               value="<?php echo htmlspecialchars($heroData['button1_link']); ?>"
                                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Tombol 2 Teks</label>
                                        <input type="text" name="button2_text" required
                                               value="<?php echo htmlspecialchars($heroData['button2_text']); ?>"
                                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Tombol 2 Link</label>
                                        <input type="text" name="button2_link" required
                                               value="<?php echo htmlspecialchars($heroData['button2_link']); ?>"
                                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t">
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition shadow-md flex items-center gap-2">
                                <i class="fas fa-save"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>