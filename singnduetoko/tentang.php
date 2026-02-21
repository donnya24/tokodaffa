<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$page_title = "Edit Tentang Kami";

$db = Database::getInstance();
$aboutData = $db->getAboutSection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token";
    } else {
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'year_established' => $_POST['year_established'],
            'customer_count' => $_POST['customer_count'],
            'feature1' => $_POST['feature1'],
            'feature2' => $_POST['feature2'],
            'feature2_note' => $_POST['feature2_note'],
            'image' => $_POST['image']
        ];
        
        if ($db->updateAboutSection($data)) {
            $_SESSION['success_message'] = "Tentang kami berhasil diupdate!";
            header('Location: tentang.php');
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
        
        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6 max-w-4xl mx-auto">
            <!-- Preview Card -->
            <div class="mb-8 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 border border-green-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-eye text-green-600"></i>
                    Preview Tampilan
                </h3>
                <div class="flex flex-col md:flex-row gap-6 items-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-store text-4xl text-gray-400"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-xl text-gray-800" id="previewTitle"><?php echo htmlspecialchars($aboutData['title']); ?></h4>
                        <p class="text-gray-600 text-sm mt-1" id="previewDesc"><?php echo substr(htmlspecialchars($aboutData['description']), 0, 100); ?>...</p>
                        <div class="flex gap-4 mt-2 text-sm text-green-600">
                            <span><i class="far fa-calendar mr-1"></i> <span id="previewYear"><?php echo htmlspecialchars($aboutData['year_established']); ?></span></span>
                            <span><i class="fas fa-users mr-1"></i> <span id="previewCustomer"><?php echo htmlspecialchars($aboutData['customer_count']); ?></span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <form method="POST" id="aboutForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="space-y-6">
                    <!-- Judul & Deskripsi -->
                    <div class="bg-gray-50 p-5 rounded-xl">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-heading text-green-600"></i>
                            Informasi Utama
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Judul Section <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" required
                                       value="<?php echo htmlspecialchars($aboutData['title']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="Contoh: Tentang Toko Daffa"
                                       oninput="updatePreview()">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="5" required
                                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                          placeholder="Deskripsi tentang toko Anda..."
                                          oninput="updatePreview()"><?php echo htmlspecialchars($aboutData['description']); ?></textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="far fa-info-circle mr-1"></i>
                                    Deskripsi akan ditampilkan di halaman tentang kami.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistik -->
                    <div class="bg-gray-50 p-5 rounded-xl">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-green-600"></i>
                            Statistik
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Tahun Berdiri <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="year_established" id="year" required
                                       value="<?php echo htmlspecialchars($aboutData['year_established']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="2020"
                                       oninput="updatePreview()">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Jumlah Pelanggan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_count" id="customer" required
                                       value="<?php echo htmlspecialchars($aboutData['customer_count']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="1000+"
                                       oninput="updatePreview()">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fitur -->
                    <div class="bg-gray-50 p-5 rounded-xl">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-star text-green-600"></i>
                            Fitur Unggulan
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Fitur 1 <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="feature1" required
                                       value="<?php echo htmlspecialchars($aboutData['feature1']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="Produk Berkualitas">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">
                                        Fitur 2 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="feature2" required
                                           value="<?php echo htmlspecialchars($aboutData['feature2']); ?>"
                                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                           placeholder="Pelayanan Ramah">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">
                                        Catatan Fitur 2 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="feature2_note" required
                                           value="<?php echo htmlspecialchars($aboutData['feature2_note']); ?>"
                                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                           placeholder="24/7 Support">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gambar -->
                    <div class="bg-gray-50 p-5 rounded-xl">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-image text-green-600"></i>
                            Gambar
                        </h3>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                Nama File Gambar <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-3">
                                <input type="text" name="image" id="image" required
                                       value="<?php echo htmlspecialchars($aboutData['image']); ?>"
                                       class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="about-image.jpg">
                                <button type="button" onclick="openFileManager()"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl transition">
                                    <i class="fas fa-folder-open mr-2"></i>Pilih File
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                Letakkan file gambar di folder <code class="bg-gray-100 px-1 py-0.5 rounded">assets/images/</code>
                            </p>
                            
                            <!-- Preview Gambar -->
                            <div class="mt-4 p-4 border-2 border-gray-200 rounded-xl bg-white">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar:</p>
                                <img src="../assets/images/<?php echo htmlspecialchars($aboutData['image']); ?>" 
                                     alt="Preview" 
                                     class="max-w-[200px] max-h-[200px] object-cover rounded-lg"
                                     onerror="this.src='https://via.placeholder.com/200x200?text=Gambar+Tidak+Ditemukan';">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Submit -->
                <div class="mt-8 pt-6 border-t flex items-center gap-3">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition shadow-md flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="dashboard.php"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-semibold transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Live preview update
function updatePreview() {
    const title = document.getElementById('title').value;
    const desc = document.getElementById('description').value;
    const year = document.getElementById('year').value;
    const customer = document.getElementById('customer').value;
    
    document.getElementById('previewTitle').textContent = title || 'Tentang Kami';
    document.getElementById('previewDesc').textContent = desc ? (desc.substring(0, 100) + '...') : 'Deskripsi tentang toko Anda...';
    document.getElementById('previewYear').textContent = year || '2020';
    document.getElementById('previewCustomer').textContent = customer || '1000+';
}

// File manager simulation (you can implement actual file manager later)
function openFileManager() {
    alert('Fitur file manager akan segera hadir!\n\nUntuk sekarang, silakan ketik nama file gambar manual.\n\nContoh: about-toko.jpg');
}

// Auto-resize textarea
const textarea = document.querySelector('textarea');
if (textarea) {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    // Trigger once
    setTimeout(() => {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }, 100);
}
</script>

<style>
/* Smooth transitions */
input, textarea, button {
    transition: all 0.2s ease;
}

/* Custom focus styles */
input:focus, textarea:focus {
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

/* Hover effects */
.bg-gray-50 {
    transition: background-color 0.2s;
}
.bg-gray-50:hover {
    background-color: #f9fafb;
}

/* Code styling */
code {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}
</style>

<?php include 'partials/footer.php'; ?>