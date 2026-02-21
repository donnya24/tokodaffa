<nav class="bg-black/90 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-green-800"
     x-data="{ menuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-green-700 to-green-900 text-white p-2 rounded-xl shadow-lg">
                    <i class="fas fa-store text-xl"></i>
                </div>
                <span class="font-bold text-xl text-white">Toko Daffa</span>
                <span class="hidden sm:inline-block bg-green-900 text-green-200 text-xs px-3 py-1 rounded-full border border-green-700">
                    Sembako, bensin eceran + lpg 3kg
                </span>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 text-green-200 font-medium">
                <a href="#beranda" class="nav-link hover:text-white transition" @click="menuOpen = false">Beranda</a>
                <a href="#produk" class="nav-link hover:text-white transition" @click="menuOpen = false">Produk</a>
                <a href="#tentang" class="nav-link hover:text-white transition" @click="menuOpen = false">Tentang</a>
                <a href="#kontak" class="nav-link hover:text-white transition" @click="menuOpen = false">Kontak</a>
            </div>
            
            <!-- WA Desktop -->
            <a href="https://wa.me/+6282264628643?text=Halo%20Toko%20Daffa%2C%20saya%20mau%20belanja"
               class="hidden md:flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-full text-sm font-semibold shadow-md transition-all">
                <i class="fab fa-whatsapp"></i>
                <span>Hubungi</span>
            </a>
            
            <!-- Mobile Menu Button -->
            <button @click="menuOpen = !menuOpen"
                    class="md:hidden text-green-200 text-2xl focus:outline-none">
                <i :class="menuOpen ? 'fas fa-times' : 'fas fa-bars'"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="menuOpen"
             @click.away="menuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden pb-4 space-y-2 text-green-200">
            <a href="#beranda" class="block py-2 hover:bg-green-900 px-2 rounded transition" @click="menuOpen = false">
                <i class="fas fa-home mr-2"></i>Beranda
            </a>
            <a href="#produk" class="block py-2 hover:bg-green-900 px-2 rounded transition" @click="menuOpen = false">
                <i class="fas fa-box mr-2"></i>Produk
            </a>
            <a href="#tentang" class="block py-2 hover:bg-green-900 px-2 rounded transition" @click="menuOpen = false">
                <i class="fas fa-store mr-2"></i>Tentang
            </a>
            <a href="#kontak" class="block py-2 hover:bg-green-900 px-2 rounded transition" @click="menuOpen = false">
                <i class="fas fa-phone mr-2"></i>Kontak
            </a>
            <a href="https://wa.me/+6282264628643?text=Halo%20Toko%20Daffa%2C%20saya%20mau%20belanja"
               class="flex items-center gap-2 bg-green-700 text-white px-3 py-2 rounded-full text-sm w-fit mt-4">
                <i class="fab fa-whatsapp"></i>
                WhatsApp
            </a>
        </div>
    </div>
</nav>