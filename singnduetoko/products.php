<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$page_title = "Manajemen Produk";

$db = Database::getInstance();
$products = $db->getProducts();

include 'partials/header.php';
include 'partials/sidebar.php';
?>

<!-- Content -->
<div class="flex-1 overflow-y-auto">
    <!-- Header -->
    <div class="bg-white shadow-sm p-6 mb-6 sticky top-0 z-10">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Produk</h2>
            <button onclick="openCreateModal()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2 shadow-md">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Produk Baru</span>
            </button>
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-6">
        <!-- Notifikasi -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                <span><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <span><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></span>
            </div>
        <?php endif; ?>
        
        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Gambar</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama Produk</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Satuan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-box-open text-5xl text-gray-300 mb-3"></i>
                                        <p class="text-lg font-medium">Belum ada produk</p>
                                        <p class="text-sm">Klik "Tambah Produk Baru" untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $index => $product): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm"><?php echo $index + 1; ?></td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="<?php echo getProductImageUrl($product['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                                             class="w-[60px] h-[60px] object-cover rounded-lg shadow-sm"
                                             onerror="this.src='<?php echo PRODUCTS_IMAGE_URL; ?>placeholder.png'">
                                    <?php else: ?>
                                        <div class="w-[60px] h-[60px] bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <?php if (!empty($product['description'])): ?>
                                        <div class="text-xs text-gray-500 mt-1"><?php echo substr(htmlspecialchars($product['description']), 0, 30); ?>...</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-medium text-green-700"><?php echo htmlspecialchars($product['price']); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($product['unit']); ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($product['is_highlight']): ?>
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1 w-fit">
                                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                                            Unggulan
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs">Reguler</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($product)); ?>)" 
                                                class="bg-blue-50 hover:bg-blue-100 text-blue-600 p-2 rounded-lg transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="openDeleteModal(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')"
                                                class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Info -->
        <div class="mt-4 text-sm text-gray-500 flex items-center gap-2">
            <i class="fas fa-database"></i>
            <span>Total <?php echo count($products); ?> produk</span>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto m-4">
        <div class="sticky top-0 bg-white p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Tambah Produk Baru</h3>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6" id="createModalContent">
            <!-- Content will be loaded via AJAX -->
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-3xl text-green-600"></i>
                <p class="mt-2 text-gray-600">Memuat form...</p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto m-4">
        <div class="sticky top-0 bg-white p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Edit Produk</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6" id="editModalContent">
            <!-- Content will be loaded via AJAX -->
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-3xl text-green-600"></i>
                <p class="mt-2 text-gray-600">Memuat form...</p>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-2xl w-full max-w-md m-4">
        <div class="p-6">
            <div class="text-center">
                <div class="bg-red-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Produk</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus produk <span id="deleteProductName" class="font-semibold"></span>?</p>
                <p class="text-sm text-red-600 mb-6">Tindakan ini tidak dapat dibatalkan!</p>
                
                <form id="deleteForm" method="POST" action="products_delete.php">
                    <input type="hidden" name="id" id="deleteProductId">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()" 
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-xl font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold transition">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Create Modal
function openCreateModal() {
    document.getElementById('createModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Load form via AJAX
    fetch('products_create_ajax.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('createModalContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('createModalContent').innerHTML = '<div class="text-center py-8 text-red-600">Gagal memuat form. Silakan refresh halaman.</div>';
        });
}

function closeCreateModal() {
    document.getElementById('createModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('createModalContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-green-600"></i><p class="mt-2 text-gray-600">Memuat form...</p></div>';
}

// Edit Modal
function openEditModal(product) {
    document.getElementById('editModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Load form via AJAX with product ID
    fetch('products_edit_ajax.php?id=' + product.id)
        .then(response => response.text())
        .then(html => {
            document.getElementById('editModalContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('editModalContent').innerHTML = '<div class="text-center py-8 text-red-600">Gagal memuat form. Silakan refresh halaman.</div>';
        });
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('editModalContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-green-600"></i><p class="mt-2 text-gray-600">Memuat form...</p></div>';
}

// Delete Modal
function openDeleteModal(id, name) {
    document.getElementById('deleteProductId').value = id;
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === createModal) {
        closeCreateModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>

<style>
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
    }
    .table-container {
        overflow-x: auto;
    }
    .hover-scale {
        transition: transform 0.2s;
    }
    .hover-scale:hover {
        transform: scale(1.02);
    }
</style>

<?php include 'partials/footer.php'; ?>