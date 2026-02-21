<?php
require_once __DIR__ . '/singnduetoko/config.php';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Toko Daffa · Sedang Maintenance</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="src/assets/images/favicon.ico" />
    <link rel="shortcut icon" href="src/assets/images/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="src/assets/images/favicon.ico" />

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="src/css/style.css" />
    
    <style>
        body {
            background: linear-gradient(135deg, #166534 0%, #22c55e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .maintenance-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 90%;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .gear-icon {
            animation: spin 4s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin: 2rem 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #166534, #22c55e);
            width: 75%;
            border-radius: 10px;
            animation: progress 2s ease-in-out infinite;
        }
        @keyframes progress {
            0% { width: 30%; }
            50% { width: 80%; }
            100% { width: 30%; }
        }
        .social-links a {
            transition: all 0.3s ease;
            display: inline-block;
        }
        .social-links a:hover {
            transform: translateY(-5px);
            color: #166534 !important;
        }
    </style>
</head>
<body>
    <div class="maintenance-card">
        <!-- Icon -->
        <div class="mb-8">
            <i class="fas fa-cog gear-icon text-7xl text-green-700"></i>
            <i class="fas fa-cog gear-icon text-7xl text-green-500 ml-4" style="animation-delay: 0.5s"></i>
        </div>
        
        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            Sedang <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-700 to-green-500">Maintenance</span>
        </h1>
        
        <!-- Description -->
        <p class="text-gray-600 text-lg mb-6">
            Kami sedang melakukan perbaikan dan peningkatan sistem untuk memberikan pelayanan yang lebih baik.
        </p>
        
        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        
        <!-- Additional Info -->
        <div class="bg-green-50 rounded-xl p-4 mb-8">
            <p class="text-green-800">
                <i class="fas fa-clock mr-2"></i>
                Estimasi waktu: -
            </p>
            <p class="text-green-600 text-sm mt-2">
                Mohon maaf atas ketidaknyamanannya. Terima kasih atas pengertian Anda.
            </p>
        </div>
    </div>

    <!-- Auto refresh setiap 30 detik -->
    <script>
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>