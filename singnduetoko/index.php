<?php
require_once 'config.php';
require_once 'database.php';

$db = Database::getInstance();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = "Invalid CSRF token";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validasi input
        if (empty($username)) {
            $errors[] = "Username harus diisi";
        }
        
        if (empty($password)) {
            $errors[] = "Password harus diisi";
        }
        
        // Verifikasi dari database (tanpa batasan percobaan)
        if (empty($errors)) {
            if ($db->verifyAdmin($username, $password)) {
                // Regenerate session ID untuk mencegah session fixation
                session_regenerate_id(true);
                
                // Set session dengan informasi tambahan
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['login_time'] = time();
                $_SESSION['login_ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                
                // Log successful login
                error_log("Successful login for user: $username from IP: " . $_SERVER['REMOTE_ADDR']);
                
                header('Location: dashboard.php');
                exit();
            } else {
                $errors[] = "Username atau password salah!";
                
                // Log failed attempt
                error_log("Failed login attempt for user: $username from IP: " . $_SERVER['REMOTE_ADDR']);
            }
        }
    }
}

$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Toko Daffa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Perbaikan cursor dan warna teks */
        input, button, .cursor-pointer {
            cursor: pointer;
        }
        
        input[type="text"], 
        input[type="password"],
        input[type="email"],
        textarea {
            cursor: text;
            color: #1f2937 !important;
            background-color: #ffffff !important;
            caret-color: #000000 !important;
        }
        
        /* Warna placeholder */
        input::placeholder,
        textarea::placeholder {
            color: #9ca3af;
            opacity: 1;
        }
        
        /* Style untuk input focus */
        input:focus, 
        button:focus,
        textarea:focus {
            outline: none;
            ring: 2px;
            ring-color: #16a34a;
            caret-color: #000000 !important;
        }
        
        /* Hilangkan background putih pada autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #1f2937 !important;
            caret-color: #000000 !important;
        }
        
        /* Untuk browser Firefox */
        @-moz-document url-prefix() {
            input, textarea {
                caret-color: #000000 !important;
            }
        }
        
        /* Animasi loading */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Smooth transitions */
        input, button, textarea {
            transition: all 0.2s ease-in-out;
        }
        
        /* Background dengan gambar lokal + overlay gelap */
        .bg-store {
            position: relative;
            background-image: url('../src/assets/images/toko-daffa.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        /* Overlay gelap di atas background */
        .bg-store::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6); /* Overlay gelap 60% */
            z-index: 1;
        }
        
        /* Konten di atas overlay */
        .bg-store > * {
            position: relative;
            z-index: 2;
        }
        
        /* Memperkecil form */
        .login-card {
            max-width: 360px;
        }
    </style>
</head>
<body class="bg-store min-h-screen flex items-center justify-center p-4">
    <!-- Overlay tambahan untuk memastikan kegelapan -->
    <div class="absolute inset-0 bg-black bg-opacity-40 z-0"></div>
    
    <div class="w-full login-card relative z-10" x-data="{ 
        showPassword: false,
        loading: false,
        username: '',
        password: '',
        errors: []
    }">
        <!-- Card Login dengan background semi transparan -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-white/20">
            <!-- Header dengan efek glassmorphism -->
            <div class="bg-gradient-to-r from-green-900/90 to-green-800/90 backdrop-blur-sm p-4 text-center border-b border-white/10">
                <div class="bg-white/20 w-16 h-16 mx-auto rounded-xl flex items-center justify-center backdrop-blur-sm mb-2 shadow-lg">
                    <i class="fas fa-store text-white text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Toko Daffa Admin</h2>
                <p class="text-green-100/80 text-xs mt-0.5">Masuk untuk mengelola konten</p>
            </div>
            
            <!-- Body -->
            <div class="p-5">
                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="bg-red-50/90 backdrop-blur-sm border-l-4 border-red-500 rounded-lg p-3 mb-4">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-circle text-red-500 text-sm mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-red-800 text-xs">Terjadi kesalahan:</h4>
                                <ul class="list-disc list-inside text-xs text-red-700 mt-1 space-y-0.5">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?php echo htmlspecialchars($err); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-4" @submit.prevent="loading = true; $el.submit()">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div>
                        <label class="block text-gray-700 text-xs font-semibold mb-1.5" for="username">
                            <i class="fas fa-user mr-1 text-green-600 text-xs"></i>Username
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="username"
                                   name="username" 
                                   required
                                   x-model="username"
                                   class="w-full border border-gray-200/80 rounded-lg px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 focus:ring-opacity-50 outline-none transition text-gray-800 bg-white/95 placeholder-gray-400"
                                   placeholder="Masukkan username"
                                   autocomplete="username"
                                   autofocus>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-xs font-semibold mb-1.5" for="password">
                            <i class="fas fa-lock mr-1 text-green-600 text-xs"></i>Password
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" 
                                   id="password"
                                   name="password" 
                                   required
                                   x-model="password"
                                   class="w-full border border-gray-200/80 rounded-lg px-3 py-2 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 focus:ring-opacity-50 outline-none transition pr-9 text-gray-800 bg-white/95 placeholder-gray-400"
                                   placeholder="Masukkan password"
                                   autocomplete="current-password">
                            <button type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition cursor-pointer text-xs"
                                    :class="{'text-green-600': showPassword}"
                                    tabindex="-1">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-700 to-green-600 hover:from-green-800 hover:to-green-700 text-white font-semibold py-2.5 rounded-lg transition duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-md flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                            :class="{ 'btn-loading': loading }"
                            :disabled="loading || !username || !password">
                        <i class="fas fa-sign-in-alt text-xs" :class="{ 'opacity-0': loading }"></i>
                        <span x-show="!loading">Login</span>
                        <span x-show="loading" class="flex items-center gap-1 text-xs">
                            <i class="fas fa-spinner fa-spin"></i>
                            Memproses...
                        </span>
                    </button>
                </form>
                
                <!-- Info Keamanan -->
                <div class="mt-4 pt-3 border-t border-gray-200/30">
                    <div class="flex justify-center gap-3 text-center">
                        <div class="text-gray-600">
                            <i class="fas fa-shield-alt text-green-600 text-xs block mb-0.5"></i>
                            <span class="text-[0.65rem]">SSL</span>
                        </div>
                        <div class="text-gray-600">
                            <i class="fas fa-clock text-green-600 text-xs block mb-0.5"></i>
                            <span class="text-[0.65rem]">Timeout</span>
                        </div>
                        <div class="text-gray-600">
                            <i class="fas fa-lock text-green-600 text-xs block mb-0.5"></i>
                            <span class="text-[0.65rem]">Encrypt</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-white/80 text-xs mt-3">
            © <?= date('Y'); ?> Toko Daffa · Admin Panel
        </p>
    </div>
    
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Auto-focus username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
        
        // Fallback jika gambar tidak ditemukan
        window.addEventListener('load', function() {
            const bgElement = document.querySelector('.bg-store');
            const testImg = new Image();
            testImg.src = '../src/assets/images/toko-daffa.png';
            testImg.onerror = function() {
                // Jika gambar gagal dimuat, beri background color fallback
                bgElement.style.backgroundImage = 'linear-gradient(-45deg, #052e16, #0f3b1a, #166534, #15803d)';
                bgElement.style.backgroundSize = '400% 400%';
                bgElement.style.animation = 'gradient 15s ease infinite';
            };
        });
    </script>
    
    <!-- Animasi gradient fallback -->
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</body>
</html>