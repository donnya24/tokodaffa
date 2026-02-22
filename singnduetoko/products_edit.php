<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$db = Database::getInstance();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['error_message'] = 'ID produk tidak valid';
    header('Location: products.php');
    exit();
}

$product = $db->getProduct($id);

if (!$product) {
    $_SESSION['error_message'] = 'Produk tidak ditemukan';
    header('Location: products.php');
    exit();
}

$page_title = "Edit Produk";
$csrf_token = generateCSRFToken();

include 'partials/header.php';
include 'partials/sidebar.php';
?>

<!-- Content -->
<div class="flex-1 overflow-y-auto">
    <!-- Header -->
    <div class="bg-white shadow-sm p-6 mb-6 sticky top-0 z-10">
        <div class="flex items-center gap-4">
            <a href="products.php" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Edit Produk</h2>
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-6">
        <!-- Notifikasi Error dari Session -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    <span><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="bg-white rounded-2xl shadow-sm p-8 max-w-4xl mx-auto">
            <form method="POST" action="products_edit_process.php" enctype="multipart/form-data" id="editForm">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kolom Kiri -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-tag text-green-600 mr-2"></i>Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required
                                   value="<?php echo htmlspecialchars($product['name']); ?>"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Harga <span class="text-red-500">*</span></label>
                                <input type="text" name="price" id="price" required
                                       value="<?php echo htmlspecialchars($product['price']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Satuan <span class="text-red-500">*</span></label>
                                <input type="text" name="unit" id="unit" required
                                       value="<?php echo htmlspecialchars($product['unit']); ?>"
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        
                        <!-- Status Produk dengan Radio Button -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-3">Status Produk</label>
                            <div class="grid grid-cols-2 gap-3" id="statusContainer">
                                <label class="flex items-center gap-3 cursor-pointer bg-gray-50 p-4 rounded-xl border-2 transition-colors status-label" data-value="reguler" id="labelReguler">
                                    <input type="radio" name="is_highlight" value="reguler" 
                                        <?php echo $product['is_highlight'] == 'reguler' ? 'checked' : ''; ?>
                                        class="w-5 h-5 text-green-600 hidden">
                                    <div>
                                        <span class="font-medium text-gray-700">Reguler</span>
                                        <p class="text-xs text-gray-500">Produk biasa</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 cursor-pointer bg-gray-50 p-4 rounded-xl border-2 transition-colors status-label" data-value="unggulan" id="labelUnggulan">
                                    <input type="radio" name="is_highlight" value="unggulan"
                                        <?php echo $product['is_highlight'] == 'unggulan' ? 'checked' : ''; ?>
                                        class="w-5 h-5 text-green-600 hidden">
                                    <div>
                                        <span class="font-medium text-gray-700">Unggulan</span>
                                        <p class="text-xs text-yellow-600">Produk prioritas</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div> <!-- Tutup Kolom Kiri -->
                    
                    <!-- Kolom Kanan -->
                    <div class="space-y-5">
                        <!-- Gambar Saat Ini -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Gambar Saat Ini</label>
                            <div class="border-2 border-gray-200 rounded-xl p-4 bg-gray-50">
                                <div class="flex items-center gap-4">
                                    <img src="<?php echo getProductImageUrl($product['image']); ?>" 
                                         alt="Current"
                                         class="w-[80px] h-[80px] object-cover rounded-lg shadow-sm"
                                         onerror="this.src='<?php echo PRODUCTS_IMAGE_URL; ?>placeholder.png';">
                                    <div>
                                        <p class="text-sm text-gray-600">Nama file:</p>
                                        <p class="text-sm font-mono text-gray-800 break-all"><?php echo $product['image'] ?: 'placeholder.png'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload Gambar Baru -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Ganti Gambar (opsional)</label>
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 bg-gray-50">
                                <div class="flex flex-wrap items-center gap-3">
                                    <button type="button" 
                                            onclick="document.getElementById('edit_image').click()"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                                        <i class="fas fa-folder-open"></i>
                                        Choose File
                                    </button>
                                    
                                    <input type="file" name="image" id="edit_image" accept="image/jpeg,image/png,image/gif,image.webp" class="hidden">
                                    
                                    <span id="selectedFileName" class="text-sm text-gray-600">
                                        <i class="fas fa-file mr-1 text-gray-400"></i>
                                        No file chosen
                                    </span>
                                </div>
                                
                                <!-- Notifikasi Error Gambar -->
                                <div id="imageError" class="text-sm text-red-600 mt-2 hidden">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span></span>
                                </div>
                                
                                <!-- Preview Gambar Baru -->
                                <div id="previewContainer" class="mt-3 hidden">
                                    <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                    <img id="previewImage" class="w-[100px] h-[100px] object-cover rounded-lg border-2 border-green-500">
                                </div>
                                
                                <p class="text-xs text-gray-500 mt-3">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: <strong>JPG, PNG, GIF, WEBP</strong> (Maks. <strong>5MB</strong>)
                                </p>
                            </div>
                        </div>
                    </div> <!-- Tutup Kolom Kanan -->
                </div> <!-- Tutup Grid -->
                
                <!-- Tombol Submit - SEKARANG DI BAWAH GRID -->
                <div class="mt-8 flex items-center gap-3 pt-6 border-t">
                    <button type="submit" id="submitBtn"
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition shadow-md flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update Produk</span>
                    </button>
                    <a href="products.php" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-semibold transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// JavaScript untuk mengupdate border
document.addEventListener('DOMContentLoaded', function() {
    const regulerInput = document.querySelector('input[name="is_highlight"][value="reguler"]');
    const unggulanInput = document.querySelector('input[name="is_highlight"][value="unggulan"]');
    
    function updateBorder() {
        if (regulerInput.checked) {
            document.getElementById('labelReguler').classList.add('border-green-500');
            document.getElementById('labelReguler').classList.remove('border-transparent');
            document.getElementById('labelUnggulan').classList.add('border-transparent');
            document.getElementById('labelUnggulan').classList.remove('border-green-500');
        } else {
            document.getElementById('labelUnggulan').classList.add('border-green-500');
            document.getElementById('labelUnggulan').classList.remove('border-transparent');
            document.getElementById('labelReguler').classList.add('border-transparent');
            document.getElementById('labelReguler').classList.remove('border-green-500');
        }
    }
    
    // Set initial border
    updateBorder();
    
    // Event listeners
    document.getElementById('labelReguler').addEventListener('click', function() {
        regulerInput.checked = true;
        updateBorder();
    });
    
    document.getElementById('labelUnggulan').addEventListener('click', function() {
        unggulanInput.checked = true;
        updateBorder();
    });
    
    regulerInput.addEventListener('change', updateBorder);
    unggulanInput.addEventListener('change', updateBorder);
});

// Preview upload gambar
document.getElementById('edit_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileNameSpan = document.getElementById('selectedFileName');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');
    const imageError = document.getElementById('imageError');
    const errorSpan = imageError.querySelector('span');
    const submitBtn = document.getElementById('submitBtn');
    
    // Reset error
    imageError.classList.add('hidden');
    submitBtn.disabled = false;
    
    if (file) {
        // Validasi ukuran (5MB)
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
            errorSpan.textContent = `Ukuran file terlalu besar (${sizeInMB}MB). Maksimal 5MB.`;
            imageError.classList.remove('hidden');
            this.value = '';
            fileNameSpan.innerHTML = '<i class="fas fa-file mr-1 text-gray-400"></i>No file chosen';
            submitBtn.disabled = true;
            return;
        }
        
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            errorSpan.textContent = `Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.`;
            imageError.classList.remove('hidden');
            this.value = '';
            fileNameSpan.innerHTML = '<i class="fas fa-file mr-1 text-gray-400"></i>No file chosen';
            submitBtn.disabled = true;
            return;
        }
        
        // Jika validasi berhasil
        fileNameSpan.innerHTML = `
            <i class="fas fa-check-circle text-green-500 mr-1"></i>
            <span class="text-green-600 font-medium">${file.name}</span>
            <span class="text-xs text-gray-500 ml-1">(${(file.size/1024).toFixed(1)} KB)</span>
        `;
        
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        fileNameSpan.innerHTML = '<i class="fas fa-file mr-1 text-gray-400"></i>No file chosen';
        previewContainer.classList.add('hidden');
    }
});
</script>

<?php include 'partials/footer.php'; ?>