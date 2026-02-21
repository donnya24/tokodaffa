<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 transition-opacity" style="display: none;">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 transform transition-all scale-95 opacity-0" id="logoutModalContent">
        <div class="p-6">
            <div class="text-center">
                <!-- Icon dengan animasi -->
                <div class="bg-red-100 w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 animate-pulse">
                    <i class="fas fa-sign-out-alt text-red-600 text-3xl"></i>
                </div>
                
                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Konfirmasi Logout</h3>
                
                <!-- Message -->
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
                
                <!-- Warning -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-lg mb-6 text-left">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                        <p class="text-xs text-yellow-700">
                            Anda akan mengakhiri sesi login saat ini. Untuk mengakses kembali, Anda perlu login ulang.
                        </p>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-3">
                    <button onclick="closeLogoutModal()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2 group">
                        <i class="fas fa-times group-hover:rotate-90 transition-transform"></i>
                        <span>Batal</span>
                    </button>
                    <a href="logout.php" 
                       class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2 group">
                        <i class="fas fa-sign-out-alt group-hover:translate-x-1 transition-transform"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal styles */
#logoutModal {
    transition: background-color 0.3s ease;
}

#logoutModalContent {
    transition: all 0.3s ease;
}

#logoutModal.bg-opacity-50 {
    background-color: rgba(0, 0, 0, 0.5);
}

/* Animasi untuk tombol */
.group:hover i {
    transform: translateX(3px);
}

/* Animasi pulse untuk icon */
@keyframes gentlePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.animate-pulse {
    animation: gentlePulse 2s infinite;
}

/* Pastikan modal di atas semua elemen */
.z-50 {
    z-index: 9999;
}
</style>