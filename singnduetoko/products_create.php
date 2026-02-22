<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$page_title = "Tambah Produk Baru";
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
            <h2 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h2>
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
            <form method="POST" action="products_create_process.php" enctype="multipart/form-data" id="createForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kolom Kiri -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-tag text-green-600 mr-2"></i>Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                   placeholder="Contoh: Beras Bagus">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Harga <span class="text-red-500">*</span></label>
                                <input type="text" name="price" id="price" required
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="Rp14.000">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Satuan <span class="text-red-500">*</span></label>
                                <input type="text" name="unit" id="unit" required
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                       placeholder="1 kg">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                                      placeholder="Deskripsi singkat tentang produk"></textarea>
                        </div>
                        
                        <!-- Status Produk dengan JavaScript -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-3">Status Produk</label>
                            <div class="grid grid-cols-2 gap-3" id="statusContainer">
                                <label class="flex items-center gap-3 cursor-pointer bg-gray-50 p-4 rounded-xl border-2 transition-colors status-label" data-value="reguler" id="labelReguler">
                                    <input type="radio" name="is_highlight" value="reguler" checked class="w-5 h-5 text-green-600 hidden">
                                    <div>
                                        <span class="font-medium text-gray-700">Reguler</span>
                                        <p class="text-xs text-gray-500">Produk biasa</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 cursor-pointer bg-gray-50 p-4 rounded-xl border-2 transition-colors status-label" data-value="unggulan" id="labelUnggulan">
                                    <input type="radio" name="is_highlight" value="unggulan" class="w-5 h-5 text-green-600 hidden">
                                    <div>
                                        <span class="font-medium text-gray-700">Unggulan</span>
                                        <p class="text-xs text-yellow-600">Produk prioritas</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kolom Kanan -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-image text-green-600 mr-2"></i>Gambar Produk <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center upload-area" id="dropArea">
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif,image.webp" required class="hidden">
                            
                            <div class="space-y-3">
                                <div class="bg-gray-100 w-20 h-20 mx-auto rounded-full flex items-center justify-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-500"></i>
                                </div>
                                
                                <button type="button" 
                                        onclick="document.getElementById('image').click()"
                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl transition font-medium">
                                    <i class="fas fa-upload mr-2"></i>Pilih Gambar
                                </button>
                                
                                <!-- Notifikasi Error Gambar -->
                                <div id="imageError" class="text-sm text-red-600 hidden">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span></span>
                                </div>
                                
                                <p class="text-xs text-gray-500" id="selectedFileName">Belum ada file dipilih</p>
                            </div>
                        </div>
                        
                        <!-- Preview -->
                        <div id="previewContainer" class="mt-4 text-center hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                            <img id="previewImage" class="max-w-[200px] max-h-[200px] object-cover rounded-lg mx-auto shadow-md border-2 border-green-500">
                            <button type="button" 
                                    onclick="clearPreview()"
                                    class="mt-2 text-xs text-red-600 hover:text-red-800">
                                <i class="fas fa-times mr-1"></i>Hapus
                            </button>
                        </div>
                        
                        <!-- Info -->
                        <div class="mt-4 bg-blue-50 p-4 rounded-xl">
                            <div class="flex gap-3">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                <div class="text-xs text-blue-700">
                                    <p class="font-medium mb-1">Ketentuan gambar:</p>
                                    <ul class="list-disc pl-4 space-y-1">
                                        <li>Ukuran maksimal <strong>5MB</strong></li>
                                        <li>Format yang diperbolehkan: <strong>JPG, PNG, GIF, WEBP</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Submit -->
                <div class="mt-8 flex items-center gap-3 pt-6 border-t">
                    <button type="submit" id="submitBtn"
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition shadow-md flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Produk</span>
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
// JavaScript untuk mengupdate border radio button
document.addEventListener('DOMContentLoaded', function() {
    const regulerInput = document.querySelector('input[name="is_highlight"][value="reguler"]');
    const unggulanInput = document.querySelector('input[name="is_highlight"][value="unggulan"]');
    
    // Fungsi untuk update border berdasarkan radio yang dipilih
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
    
    // Set initial border (Reguler default)
    updateBorder();
    
    // Tambahkan event listener ke label Reguler
    document.getElementById('labelReguler').addEventListener('click', function() {
        regulerInput.checked = true;
        updateBorder();
    });
    
    // Tambahkan event listener ke label Unggulan
    document.getElementById('labelUnggulan').addEventListener('click', function() {
        unggulanInput.checked = true;
        updateBorder();
    });
    
    // Tambahkan event listener ke radio button langsung
    regulerInput.addEventListener('change', updateBorder);
    unggulanInput.addEventListener('change', updateBorder);
});

// Preview upload dengan validasi
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const imageError = document.getElementById('imageError');
    const errorSpan = imageError.querySelector('span');
    const selectedFileName = document.getElementById('selectedFileName');
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
            selectedFileName.textContent = 'Belum ada file dipilih';
            submitBtn.disabled = true;
            return;
        }
        
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            errorSpan.textContent = `Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.`;
            imageError.classList.remove('hidden');
            this.value = '';
            selectedFileName.textContent = 'Belum ada file dipilih';
            submitBtn.disabled = true;
            return;
        }
        
        // Jika validasi berhasil
        selectedFileName.textContent = file.name;
        selectedFileName.classList.add('text-green-600', 'font-medium');
        
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('previewContainer').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        selectedFileName.textContent = 'Belum ada file dipilih';
        selectedFileName.classList.remove('text-green-600', 'font-medium');
    }
});

function clearPreview() {
    document.getElementById('image').value = '';
    document.getElementById('previewContainer').classList.add('hidden');
    document.getElementById('selectedFileName').textContent = 'Belum ada file dipilih';
    document.getElementById('selectedFileName').classList.remove('text-green-600', 'font-medium');
    document.getElementById('imageError').classList.add('hidden');
    document.getElementById('submitBtn').disabled = false;
}

// Validasi sebelum submit form
document.getElementById('createForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('image');
    const imageError = document.getElementById('imageError');
    
    if (!fileInput.files || fileInput.files.length === 0) {
        e.preventDefault();
        imageError.querySelector('span').textContent = 'Gambar produk harus diupload';
        imageError.classList.remove('hidden');
        return false;
    }
});

// Drag and drop
const dropArea = document.getElementById('dropArea');
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight() {
    dropArea.classList.add('border-green-500', 'bg-green-50');
}

function unhighlight() {
    dropArea.classList.remove('border-green-500', 'bg-green-50');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        document.getElementById('image').files = files;
        const event = new Event('change', { bubbles: true });
        document.getElementById('image').dispatchEvent(event);
    }
}
</script>

<style>
.upload-area {
    transition: all 0.3s;
}
.upload-area:hover {
    border-color: #16a34a;
    background-color: #f0fdf4;
}
#previewImage {
    max-width: 200px;
    max-height: 200px;
    object-fit: cover;
}
.status-label {
    transition: all 0.2s ease;
}
.status-label:hover {
    background-color: #e5e7eb;
}
</style>

<?php include 'partials/footer.php'; ?>