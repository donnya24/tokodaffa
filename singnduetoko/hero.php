<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$page_title = "Edit Hero Section"; // Tambahkan page title

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
            $_SESSION['success_message'] = "Hero section berhasil diupdate!";
            header('Location: hero.php');
            exit();
        } else {
            $error = "Gagal mengupdate data";
        }
    }
}

$csrf_token = generateCSRFToken();

include 'partials/header.php';
include 'partials/sidebar.php';
?>

<!-- Content -->
<div class="flex-1 overflow-y-auto">
    
    <!-- Content -->
    <div class="p-6">
        <!-- Notifikasi -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                <span><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <!-- Preview Card -->
        <div class="mb-8 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 border border-green-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-eye text-green-600"></i>
                Preview Tampilan
            </h3>
            <div class="flex flex-col md:flex-row gap-6 items-center">
                <div class="w-32 h-32 bg-gray-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                <div>
                    <div class="inline-block bg-green-800/70 text-green-100 px-3 py-1 rounded-full text-xs font-semibold mb-2 border border-green-600">
                        <span id="previewBadge"><?php echo htmlspecialchars($heroData['badge']); ?></span>
                    </div>
                    <h4 class="font-bold text-xl text-gray-800">
                        <span id="previewTitle1"><?php echo htmlspecialchars($heroData['title1']); ?></span> 
                        <span class="text-green-600" id="previewTitle2"><?php echo htmlspecialchars($heroData['title2']); ?></span>
                    </h4>
                    <p class="text-gray-600 text-sm mt-1" id="previewSubtitle"><?php echo htmlspecialchars($heroData['subtitle']); ?></p>
                    <div class="flex gap-4 mt-2 text-sm text-green-600">
                        <span><i class="far fa-clock mr-1"></i> <span id="previewTime"><?php echo htmlspecialchars($heroData['open_time']); ?> - <?php echo htmlspecialchars($heroData['close_time']); ?> WIB</span></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Badge Text</label>
                        <input type="text" name="badge" id="badge" required
                               value="<?php echo htmlspecialchars($heroData['badge']); ?>"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                               oninput="updatePreview()">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Judul 1</label>
                            <input type="text" name="title1" id="title1" required
                                   value="<?php echo htmlspecialchars($heroData['title1']); ?>"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                   oninput="updatePreview()">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Judul 2</label>
                            <input type="text" name="title2" id="title2" required
                                   value="<?php echo htmlspecialchars($heroData['title2']); ?>"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                   oninput="updatePreview()">
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">Subjudul</label>
                        <textarea name="subtitle" id="subtitle" rows="3" required
                                  class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                  oninput="updatePreview()"><?php echo htmlspecialchars($heroData['subtitle']); ?></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Jam Buka</label>
                        <input type="text" name="open_time" id="open_time" required
                               value="<?php echo htmlspecialchars($heroData['open_time']); ?>"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                               oninput="updatePreview()">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Jam Tutup</label>
                        <input type="text" name="close_time" id="close_time" required
                               value="<?php echo htmlspecialchars($heroData['close_time']); ?>"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                               oninput="updatePreview()">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Background Image</label>
                        <input type="text" name="background_image" required
                               value="<?php echo htmlspecialchars($heroData['background_image']); ?>"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            Nama file gambar di folder src/assets/images/
                        </p>
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

<script>
// Live preview update
function updatePreview() {
    const badge = document.getElementById('badge').value;
    const title1 = document.getElementById('title1').value;
    const title2 = document.getElementById('title2').value;
    const subtitle = document.getElementById('subtitle').value;
    const openTime = document.getElementById('open_time').value;
    const closeTime = document.getElementById('close_time').value;
    
    document.getElementById('previewBadge').textContent = badge || 'Badge';
    document.getElementById('previewTitle1').textContent = title1 || 'Toko';
    document.getElementById('previewTitle2').textContent = title2 || 'Daffa';
    document.getElementById('previewSubtitle').textContent = subtitle || 'Subtitle';
    document.getElementById('previewTime').textContent = (openTime || '07:00') + ' - ' + (closeTime || '21:30') + ' WIB';
}

// Auto-resize textarea
const textarea = document.querySelector('textarea');
if (textarea) {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}
</script>

<style>
input, textarea, button {
    transition: all 0.2s ease;
}
input:focus, textarea:focus {
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}
</style>

<?php include 'partials/footer.php'; ?>