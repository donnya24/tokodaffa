<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$page_title = "Edit Informasi Kontak";

$db = Database::getInstance();
$contactData = $db->getContactSection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token";
    } else {
        $data = [
            'address' => $_POST['address'],
            'whatsapp_number' => $_POST['whatsapp_number'],
            'whatsapp_display' => $_POST['whatsapp_display'],
            'maps_embed_url' => $_POST['maps_embed_url'],
            'maps_link' => $_POST['maps_link']
        ];
        
        if ($db->updateContactSection($data)) {
            $_SESSION['success_message'] = "Informasi kontak berhasil diupdate!";
            header('Location: kontak.php');
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
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-eye text-blue-600"></i>
                    Preview Tampilan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Alamat</p>
                                <p class="text-gray-800 text-sm" id="previewAddress"><?php echo htmlspecialchars(substr($contactData['address'], 0, 50)) . '...'; ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 mt-3">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <i class="fab fa-whatsapp text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">WhatsApp</p>
                                <p class="text-gray-800 text-sm" id="previewWhatsapp"><?php echo htmlspecialchars($contactData['whatsapp_display']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="bg-gray-100 h-24 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map text-3xl text-gray-400"></i>
                            <span class="ml-2 text-sm text-gray-500">Preview Maps</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <form method="POST" id="contactForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="space-y-6">
                    <!-- Alamat -->
                    <div class="bg-gray-50 p-5 rounded-xl">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-blue-600"></i>
                            Alamat Toko
                        </h3>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="3" required
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                      placeholder="Jl. Contoh No. 123, Kota, Provinsi"
                                      oninput="updatePreview()"><?php echo htmlspecialchars($contactData['address']); ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="far fa-info-circle mr-1"></i>
                                Alamat lengkap toko akan ditampilkan di halaman kontak.
                            </p>
                        </div>
                    </div>
                    
                    <!-- WhatsApp -->
                    <div class="bg-gray-50 p-5 rounded-xl">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fab fa-whatsapp text-green-600"></i>
                            WhatsApp
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Nomor WhatsApp (Link) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="whatsapp_number" id="whatsapp_number" required
                                       value="<?php echo htmlspecialchars($contactData['whatsapp_number']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="6281234567890"
                                       oninput="updatePreview()">
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                    Format: 628xxxxxxxxx (tanpa +, tanpa spasi)
                                </p>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Nomor WhatsApp (Tampilan) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="whatsapp_display" id="whatsapp_display" required
                                       value="<?php echo htmlspecialchars($contactData['whatsapp_display']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="+62 812-3456-7890"
                                       oninput="updatePreview()">
                                <p class="text-xs text-gray-500 mt-1">
                                    Format yang ditampilkan ke pengunjung
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Google Maps -->
                    <div class="bg-gray-50 p-5 rounded-xl">
                        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-map text-red-600"></i>
                            Google Maps
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Maps Embed URL <span class="text-red-500">*</span>
                                </label>
                                <textarea name="maps_embed_url" id="maps_embed" rows="3" required
                                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition font-mono text-sm"
                                          placeholder="https://www.google.com/maps/embed?pb=..."><?php echo htmlspecialchars($contactData['maps_embed_url']); ?></textarea>
                                <p class="text-xs text-gray-500 mt-1 flex items-start gap-1">
                                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                    <span>URL untuk embed peta. Dapatkan dari Google Maps dengan klik "Bagikan" → "Sematkan peta".</span>
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    Maps Link <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="maps_link" id="maps_link" required
                                       value="<?php echo htmlspecialchars($contactData['maps_link']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="https://goo.gl/maps/...">
                                <p class="text-xs text-gray-500 mt-1">
                                    Link untuk tombol "Buka di Google Maps"
                                </p>
                            </div>
                        </div>
                        
                        <!-- Preview Maps -->
                        <div class="mt-4 p-4 bg-white rounded-xl border-2 border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-map-pin text-red-500"></i>
                                Preview Maps:
                            </p>
                            <?php if (!empty($contactData['maps_embed_url'])): ?>
                                <div class="aspect-video w-full bg-gray-100 rounded-lg overflow-hidden">
                                    <iframe 
                                        src="<?php echo htmlspecialchars($contactData['maps_embed_url']); ?>" 
                                        class="w-full h-full"
                                        style="border:0;" 
                                        allowfullscreen="" 
                                        loading="lazy">
                                    </iframe>
                                </div>
                            <?php else: ?>
                                <div class="aspect-video w-full bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-map-marked-alt text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-500">Preview maps akan muncul di sini</p>
                                    </div>
                                </div>
                            <?php endif; ?>
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
    const address = document.getElementById('address').value;
    const whatsappDisplay = document.getElementById('whatsapp_display').value;
    
    document.getElementById('previewAddress').textContent = address ? (address.substring(0, 50) + '...') : 'Jl. Contoh No. 123, Kota, Provinsi';
    document.getElementById('previewWhatsapp').textContent = whatsappDisplay || '+62 812-3456-7890';
}

// Auto-resize textarea
const textareas = document.querySelectorAll('textarea');
textareas.forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    // Trigger once
    setTimeout(() => {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }, 100);
});

// Validate WhatsApp number format
document.getElementById('whatsapp_number').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
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

/* Maps preview */
iframe {
    border-radius: 0.5rem;
}
</style>

<?php include 'partials/footer.php'; ?>