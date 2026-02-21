<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$csrf_token = generateCSRFToken();
?>

<form id="createProductForm" method="POST" action="products_create_process.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Kolom Kiri -->
        <div class="space-y-5">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-tag text-green-600 mr-2"></i>Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="create_name" required
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                       placeholder="Contoh: Beras Bagus">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Harga</label>
                    <input type="text" name="price" id="create_price" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                           placeholder="Rp14.000">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Satuan</label>
                    <input type="text" name="unit" id="create_unit" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                           placeholder="1 kg">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" id="create_description" rows="4"
                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition"
                          placeholder="Deskripsi singkat tentang produk"></textarea>
            </div>
            
            <div>
                <label class="flex items-center gap-3 cursor-pointer bg-gray-50 p-4 rounded-xl">
                    <input type="checkbox" name="is_highlight" id="create_is_highlight" value="1"
                           class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                    <span class="font-medium text-gray-700">Jadikan produk unggulan</span>
                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Highlight</span>
                </label>
            </div>
        </div>
        
        <!-- Kolom Kanan - Upload Gambar -->
        <div>
            <label class="block text-gray-700 font-semibold mb-2">
                <i class="fas fa-image text-green-600 mr-2"></i>Gambar Produk <span class="text-red-500">*</span>
            </label>
            
            <!-- Upload Area -->
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center upload-area" id="dropArea">
                <input type="file" name="image" id="create_image" accept="image/jpeg,image/png,image/gif,image/webp" required
                       class="hidden">
                
                <div class="space-y-3">
                    <div class="bg-gray-100 w-20 h-20 mx-auto rounded-full flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-500"></i>
                    </div>
                    
                    <button type="button" 
                            onclick="document.getElementById('create_image').click()"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl transition font-medium">
                        <i class="fas fa-upload mr-2"></i>Pilih Gambar
                    </button>
                    
                    <p class="text-xs text-gray-500">
                        Format: JPG, PNG, GIF, WEBP (Maks. 5MB)
                    </p>
                    <p class="text-xs text-gray-400" id="selectedFileName">Belum ada file dipilih</p>
                </div>
            </div>
            
            <!-- Preview -->
            <div id="previewContainer" class="mt-4 text-center hidden">
                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                <img id="previewImage" class="max-w-[200px] max-h-[200px] object-cover rounded-lg mx-auto shadow-md">
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
                            <li>Ukuran maksimal 5MB</li>
                            <li>Format: JPG, PNG, GIF, WEBP</li>
                            <li>Gambar akan disimpan dengan nama unik</li>
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
        <button type="button" onclick="closeCreateModal()"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-semibold transition">
            Batal
        </button>
    </div>
</form>

<script>
// Preview dan upload handling
document.getElementById('create_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validasi ukuran
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file maksimal 5MB');
            this.value = '';
            document.getElementById('selectedFileName').textContent = 'Belum ada file dipilih';
            return;
        }
        
        // Validasi tipe
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar');
            this.value = '';
            document.getElementById('selectedFileName').textContent = 'Belum ada file dipilih';
            return;
        }
        
        document.getElementById('selectedFileName').textContent = file.name;
        
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('previewContainer').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

function clearPreview() {
    document.getElementById('create_image').value = '';
    document.getElementById('previewContainer').classList.add('hidden');
    document.getElementById('selectedFileName').textContent = 'Belum ada file dipilih';
}

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
        document.getElementById('create_image').files = files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        document.getElementById('create_image').dispatchEvent(event);
    }
}

// Form submission with AJAX
document.getElementById('createProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Disable button dan tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    
    fetch('products_create_process.php', {
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
    #previewImage {
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 2px solid #e5e7eb;
    }
</style>