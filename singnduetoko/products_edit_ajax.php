<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$db = Database::getInstance();

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    echo '<div class="text-center py-8 text-red-600">ID produk tidak valid</div>';
    exit();
}

// Ambil data produk
$product = $db->getProduct($id);

if (!$product) {
    echo '<div class="text-center py-8 text-red-600">Produk tidak ditemukan</div>';
    exit();
}

$csrf_token = generateCSRFToken();
?>

<form id="editProductForm" method="POST" action="products_edit_process.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Kolom Kiri -->
        <div class="space-y-5">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama Produk</label>
                <input type="text" name="name" id="edit_name" required
                       value="<?php echo htmlspecialchars($product['name']); ?>"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Harga</label>
                    <input type="text" name="price" id="edit_price" required
                           value="<?php echo htmlspecialchars($product['price']); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Satuan</label>
                    <input type="text" name="unit" id="edit_unit" required
                           value="<?php echo htmlspecialchars($product['unit']); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" id="edit_description" rows="4"
                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div>
                <label class="flex items-center gap-3 cursor-pointer bg-gray-50 p-4 rounded-xl">
                    <input type="checkbox" name="is_highlight" id="edit_is_highlight" value="1"
                           <?php echo $product['is_highlight'] ? 'checked' : ''; ?>
                           class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                    <span class="font-medium text-gray-700">Produk unggulan</span>
                </label>
            </div>
        </div>
        
        <!-- Kolom Kanan -->
        <div class="space-y-5">
            <!-- Gambar Saat Ini -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Gambar Saat Ini</label>
                <div class="border-2 border-gray-200 rounded-xl p-4 bg-gray-50 text-center">
                    <img src="<?php echo getProductImageUrl($product['image']); ?>" 
                         alt="Current"
                         class="max-w-[150px] max-h-[150px] object-cover rounded-lg mx-auto mb-2"
                         onerror="this.src='<?php echo PRODUCTS_IMAGE_URL; ?>placeholder.png';">
                    <p class="text-xs text-gray-500"><?php echo $product['image']; ?></p>
                    <input type="hidden" name="existing_image" value="<?php echo $product['image']; ?>">
                </div>
            </div>
            
            <!-- Upload Gambar Baru -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Ganti Gambar (opsional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center upload-area" id="editDropArea">
                    
                    <input type="file" name="image" id="edit_image" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden">
                    
                    <div class="space-y-3">
                        <div class="bg-gray-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-500"></i>
                        </div>
                        
                        <button type="button" 
                                onclick="document.getElementById('edit_image').click()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm transition">
                            <i class="fas fa-upload mr-2"></i>Pilih Gambar Baru
                        </button>
                        
                        <p class="text-xs text-gray-500" id="editSelectedFileName">Belum ada file dipilih</p>
                    </div>
                </div>
            </div>
            
            <!-- Preview Gambar Baru -->
            <div id="editPreviewContainer" class="text-center hidden">
                <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru:</p>
                <img id="editPreviewImage" class="max-w-[200px] max-h-[200px] object-cover rounded-lg mx-auto">
                <button type="button" 
                        onclick="clearEditPreview()"
                        class="mt-2 text-xs text-red-600 hover:text-red-800">
                    <i class="fas fa-times mr-1"></i>Batalkan
                </button>
            </div>
        </div>
    </div>
    
    <!-- Tombol Submit -->
    <div class="mt-8 flex items-center gap-3 pt-6 border-t">
        <button type="submit" id="editSubmitBtn"
                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition shadow-md flex items-center gap-2">
            <i class="fas fa-save"></i>
            <span>Update Produk</span>
        </button>
        <button type="button" onclick="closeEditModal()"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-semibold transition">
            Batal
        </button>
    </div>
</form>

<script>
// Preview untuk edit
document.getElementById('edit_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validasi ukuran
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file maksimal 5MB');
            this.value = '';
            document.getElementById('editSelectedFileName').textContent = 'Belum ada file dipilih';
            return;
        }
        
        // Validasi tipe
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar');
            this.value = '';
            document.getElementById('editSelectedFileName').textContent = 'Belum ada file dipilih';
            return;
        }
        
        document.getElementById('editSelectedFileName').textContent = file.name;
        
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('editPreviewImage').src = e.target.result;
            document.getElementById('editPreviewContainer').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

function clearEditPreview() {
    document.getElementById('edit_image').value = '';
    document.getElementById('editPreviewContainer').classList.add('hidden');
    document.getElementById('editSelectedFileName').textContent = 'Belum ada file dipilih';
}

// Drag and drop untuk edit
const editDropArea = document.getElementById('editDropArea');
if (editDropArea) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        editDropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        editDropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        editDropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        editDropArea.classList.add('border-green-500', 'bg-green-50');
    }
    
    function unhighlight() {
        editDropArea.classList.remove('border-green-500', 'bg-green-50');
    }
    
    editDropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            document.getElementById('edit_image').files = files;
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            document.getElementById('edit_image').dispatchEvent(event);
        }
    }
}

// Form submission with AJAX
document.getElementById('editProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('editSubmitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Disable button dan tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    
    fetch('products_edit_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>

<style>
    .upload-area {
        transition: all 0.3s;
    }
    .upload-area:hover {
        border-color: #16a34a;
        background-color: #f0fdf4;
    }
    #editPreviewImage {
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 2px solid #e5e7eb;
    }
</style>