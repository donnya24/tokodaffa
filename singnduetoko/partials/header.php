<?php if (!isset($page_title)) $page_title = "Admin Panel"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $page_title ?> - Toko Daffa</title>
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/singnduetoko/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="green">
    <meta name="apple-mobile-web-app-title" content="TokoDaffa">
    <meta name="theme-color" content="#059669">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- iOS Icons - Gunakan icon 96x96 untuk semua ukuran -->
    <link rel="apple-touch-icon" href="/src/assets/images/icons/icon-96x96.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/src/assets/images/icons/icon-96x96.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/src/assets/images/icons/icon-96x96.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/src/assets/images/icons/icon-96x96.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/src/assets/images/icons/icon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/src/assets/images/icons/icon-96x96.png">
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Custom CSS untuk PWA -->
    <style>
        /* iOS specific styles */
        @supports (-webkit-touch-callout: none) {
            body {
                /* Prevent pull-to-refresh on iOS */
                overscroll-behavior-y: contain;
            }
        }
        
        /* Loading animation untuk PWA */
        .pwa-loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }
        
        .pwa-loading.active {
            display: flex;
        }
        
        .pwa-loading .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #059669;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Smooth transitions */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Notifikasi update */
        .update-notification {
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
    
    <!-- Register Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/singnduetoko/sw.js')
                    .then(function(registration) {
                        console.log('✅ ServiceWorker registered: ', registration.scope);
                        
                        registration.addEventListener('updatefound', function() {
                            const newWorker = registration.installing;
                            console.log('🔄 New service worker found:', newWorker);
                            
                            newWorker.addEventListener('statechange', function() {
                                if (newWorker.state === 'installed') {
                                    if (navigator.serviceWorker.controller) {
                                        console.log('📦 New content available. Please refresh.');
                                        showUpdateNotification();
                                    }
                                }
                            });
                        });
                    })
                    .catch(function(err) {
                        console.log('❌ ServiceWorker registration failed: ', err);
                    });
            });
            
            window.addEventListener('online', function() {
                console.log('📶 Kembali online');
                document.getElementById('offlineIndicator').classList.add('hidden');
                document.body.classList.remove('offline');
            });
            
            window.addEventListener('offline', function() {
                console.log('📴 Sedang offline');
                document.getElementById('offlineIndicator').classList.remove('hidden');
                document.body.classList.add('offline');
            });
        }
        
        function showUpdateNotification() {
            const notif = document.createElement('div');
            notif.className = 'update-notification fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-3';
            notif.innerHTML = `
                <i class="fas fa-download"></i>
                <span>Update tersedia!</span>
                <button onclick="location.reload()" class="bg-white text-green-600 px-3 py-1 rounded-lg text-sm font-semibold hover:bg-green-50 transition ml-2">
                    Refresh
                </button>
            `;
            document.body.appendChild(notif);
            
            setTimeout(() => {
                notif.remove();
            }, 10000);
        }
        
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
            document.documentElement.classList.add('pwa-mode');
            console.log('📱 Running as PWA');
        }
    </script>
    
    <!-- Loading indicator -->
    <div class="pwa-loading" id="pwaLoading">
        <div class="spinner"></div>
    </div>
</head>
<body class="bg-gray-50">
    <!-- Offline indicator -->
    <div id="offlineIndicator" class="hidden fixed top-0 left-0 right-0 bg-yellow-500 text-white text-center py-1 text-sm z-50">
        <i class="fas fa-wifi-slash mr-2"></i> Anda sedang offline. Data mungkin tidak dapat diperbarui.
    </div>
    
    <div class="flex h-screen">