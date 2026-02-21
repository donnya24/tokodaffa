<?php
require_once __DIR__ . '/singnduetoko/config.php';
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
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased"
      x-data="{ notif: true }"
      x-init="setTimeout(() => notif = false, 4500)">
    
    <!-- INCLUDE LANGSUNG - PASTI JALAN -->
    <?php include 'src/components/navbar.php'; ?>
    <?php include 'src/components/hero.php'; ?>
    <?php include 'src/components/produk.php'; ?>
    <?php include 'src/components/tentang.php'; ?>
    <?php include 'src/components/kontak.php'; ?>
    <?php include 'src/components/footer.php'; ?>

    <!-- Floating WA -->
    <a href="https://wa.me/6282264628643?text=Halo%20Toko%20Daffa%2C%20saya%20mau%20belanja%20sembako%20dan%20gas%203kg"
       class="whatsapp-float fixed bottom-6 right-6 bg-green-700 text-white p-4 rounded-full shadow-2xl text-3xl leading-none z-20 hover:bg-green-800">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Notifikasi -->
    <div x-show="notif"
         x-transition.duration.500
         class="fixed top-20 right-4 bg-black border-l-4 border-green-600 text-green-200 p-4 rounded-lg shadow-xl z-30 max-w-xs">
        <p class="font-medium flex items-center gap-2">
            <i class="fas fa-store text-green-400"></i> Selamat datang di situs kami!
        </p>
        <p class="text-xs mt-1">Toko Daffa siap melayani kebutuhan Anda.</p>
    </div>

</body>
</html>