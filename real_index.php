<?php
// Load konfigurasi dan database
require_once __DIR__ . '/singnduetoko/config.php';
require_once __DIR__ . '/singnduetoko/database.php';

// Inisialisasi database
$db = Database::getInstance();

// Catat pengunjung (hanya untuk halaman utama, bukan admin)
$db->recordVisitor();

// Ambil data untuk ditampilkan di frontend
$products = $db->getProducts(); // Ambil semua produk
$hero = $db->getHeroSection(); // Ambil data hero section
$about = $db->getAboutSection(); // Ambil data tentang
$contact = $db->getContactSection(); // Ambil data kontak
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Toko Daffa · Sembako, Bensin eceran & LPG 3kg</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="src/assets/images/favicon.ico" />
    <link rel="shortcut icon" href="src/assets/images/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="src/assets/images/favicon.ico" />

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js (masih digunakan untuk notifikasi) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Chart.js untuk grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="src/css/style.css" />
    <style>
        #navbar {
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .whatsapp-float {
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 128, 0, 0.3);
        }
        .whatsapp-float:hover {
            transform: scale(1.1) rotate(5deg);
            background-color: #15803d !important;
        }
        /* Smooth scroll untuk anchor links */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased"
      x-data="{ notif: true }"
      x-init="setTimeout(() => notif = false, 4500)">
    
    <!-- NAVBAR - dengan data dari database -->
    <?php 
    $hero_data = $hero;
    include 'src/components/navbar.php'; 
    ?>
    
    <!-- HERO SECTION - dengan data dari database -->
    <?php 
    $hero_data = $hero;
    include 'src/components/hero.php'; 
    ?>
    
    <!-- PRODUK SECTION - dengan data dari database -->
    <?php 
    $products_data = $products;
    include 'src/components/produk.php'; 
    ?>
    
    <!-- TENTANG SECTION - dengan data dari database -->
    <?php 
    $about_data = $about;
    include 'src/components/tentang.php'; 
    ?>
    
    <!-- KONTAK SECTION - dengan data dari database -->
    <?php 
    $contact_data = $contact;
    include 'src/components/kontak.php'; 
    ?>
    
    <!-- FOOTER -->
    <?php include 'src/components/footer.php'; ?>

    <!-- Floating WA -->
    <a href="https://wa.me/6282264628643?text=Halo%20Toko%20Daffa%2C%20saya%20mau%20belanja%20sembako%20dan%20gas%203kg"
       class="whatsapp-float fixed bottom-6 right-6 bg-green-700 text-white p-4 rounded-full shadow-2xl text-3xl leading-none z-20 hover:bg-green-800"
       target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Notifikasi Selamat Datang -->
    <div x-show="notif"
         x-transition.duration.500
         class="fixed top-20 right-4 bg-black border-l-4 border-green-600 text-green-200 p-4 rounded-lg shadow-xl z-30 max-w-xs">
        <p class="font-medium flex items-center gap-2">
            <i class="fas fa-store text-green-400"></i> Selamat datang di situs kami!
        </p>
        <p class="text-xs mt-1">Toko Daffa siap melayani kebutuhan Anda.</p>
    </div>

    <!-- Script tambahan untuk interaktivitas -->
    <script>
        // Highlight active menu berdasarkan scroll position
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            
            window.addEventListener('scroll', function() {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (scrollY >= (sectionTop - 200)) {
                        current = section.getAttribute('id');
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('text-green-600', 'font-semibold');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('text-green-600', 'font-semibold');
                    }
                });
            });
        });
    </script>
</body>
</html>