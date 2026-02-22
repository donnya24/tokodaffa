<?php
// singnduetoko/database.php

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $db   = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];

            $dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode=require";

            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_TIMEOUT, 30);

        } catch (PDOException $e) {
            // Tampilkan pesan sederhana untuk semua environment
            die("
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Koneksi Database Gagal</title>
                    <style>
                        body { 
                            font-family: 'Arial', sans-serif; 
                            background: #f3f4f6; 
                            display: flex; 
                            justify-content: center; 
                            align-items: center; 
                            height: 100vh; 
                            margin: 0; 
                        }
                        .error-box { 
                            background: white; 
                            padding: 40px; 
                            border-radius: 16px; 
                            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
                            text-align: center; 
                            max-width: 400px; 
                        }
                        .icon { 
                            font-size: 64px; 
                            margin-bottom: 20px; 
                        }
                        h1 { 
                            color: #1f2937; 
                            font-size: 24px; 
                            margin-bottom: 10px; 
                        }
                        p { 
                            color: #6b7280; 
                            margin-bottom: 20px; 
                        }
                        .btn { 
                            background: #059669; 
                            color: white; 
                            padding: 12px 24px; 
                            border-radius: 8px; 
                            text-decoration: none; 
                            display: inline-block; 
                        }
                        .btn:hover { 
                            background: #047857; 
                        }
                    </style>
                </head>
                <body>
                    <div class='error-box'>
                        <div class='icon'>⚠️</div>
                        <h1>Koneksi Database Gagal</h1>
                        <p>Maaf, saat ini terjadi gangguan pada koneksi database. Silakan coba beberapa saat lagi.</p>
                        <a href='javascript:location.reload()' class='btn'>Coba Lagi</a>
                    </div>
                </body>
                </html>
            ");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function testConnection() {
        try {
            $stmt = $this->pdo->query("SELECT 1");
            return $stmt->fetchColumn() == 1;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================= PRODUCTS =================

    public function getProducts() {
        try {
            return $this->pdo
                ->query("SELECT * FROM products ORDER BY id DESC")
                ->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getProduct($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createProduct($data) {
        try {
            $is_highlight = 'reguler';
            if (isset($data['is_highlight'])) {
                if ($data['is_highlight'] === 'unggulan' || $data['is_highlight'] === '1' || $data['is_highlight'] === 1) {
                    $is_highlight = 'unggulan';
                } else {
                    $is_highlight = 'reguler';
                }
            }
            
            $sql = "INSERT INTO products 
                    (name, price, unit, description, image, is_highlight, created_at, updated_at)
                    VALUES 
                    (:name, :price, :unit, :description, :image, :is_highlight, NOW(), NOW())
                    RETURNING id";

            $stmt = $this->pdo->prepare($sql);
            
            $params = [
                ':name' => $data['name'],
                ':price' => $data['price'],
                ':unit' => $data['unit'],
                ':description' => $data['description'] ?? '',
                ':image' => $data['image'],
                ':is_highlight' => $is_highlight
            ];
            
            $stmt->execute($params);
            return $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateProduct($id, $data) {
        try {
            $is_highlight = 'reguler';
            if (isset($data['is_highlight'])) {
                if ($data['is_highlight'] === 'unggulan' || $data['is_highlight'] === '1' || $data['is_highlight'] === 1) {
                    $is_highlight = 'unggulan';
                } else {
                    $is_highlight = 'reguler';
                }
            }
            
            $sql = "UPDATE products SET
                    name = :name,
                    price = :price,
                    unit = :unit,
                    description = :description,
                    image = :image,
                    is_highlight = :is_highlight,
                    updated_at = NOW()
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            
            $params = [
                ':id' => $id,
                ':name' => $data['name'],
                ':price' => $data['price'],
                ':unit' => $data['unit'],
                ':description' => $data['description'] ?? '',
                ':image' => $data['image'],
                ':is_highlight' => $is_highlight
            ];
            
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteProduct($id) {
        try {
            $check = $this->pdo->prepare("SELECT id FROM products WHERE id = ?");
            $check->execute([$id]);
            $product = $check->fetch();
            
            if (!$product) {
                return false;
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
            return $stmt->execute([$id]);
            
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================= VISITOR STATISTICS =================

    public function recordVisitor() {
        try {
            $today = date('Y-m-d');
            
            $check = $this->pdo->prepare("SELECT id FROM visitors WHERE visit_date = ?");
            $check->execute([$today]);
            $exists = $check->fetch();
            
            if ($exists) {
                $stmt = $this->pdo->prepare("UPDATE visitors SET visit_count = visit_count + 1, updated_at = NOW() WHERE visit_date = ?");
                $stmt->execute([$today]);
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO visitors (visit_date, visit_count) VALUES (?, 1)");
                $stmt->execute([$today]);
            }
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getWeeklyVisitors() {
        try {
            $sql = "SELECT 
                        TO_CHAR(visit_date, 'Day') as day_name,
                        EXTRACT(DOW FROM visit_date) as day_num,
                        visit_count
                    FROM visitors 
                    WHERE visit_date >= CURRENT_DATE - INTERVAL '6 days'
                    ORDER BY visit_date ASC";
            
            $stmt = $this->pdo->query($sql);
            $results = $stmt->fetchAll();
            
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $visitor_data = array_fill(0, 7, 0);
            
            foreach ($results as $row) {
                $day_index = intval($row['day_num']);
                $adjusted_index = ($day_index == 0) ? 6 : $day_index - 1;
                $visitor_data[$adjusted_index] = intval($row['visit_count']);
            }
            
            return [
                'labels' => $days,
                'data' => $visitor_data
            ];
            
        } catch (PDOException $e) {
            return [
                'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                'data' => [0, 0, 0, 0, 0, 0, 0]
            ];
        }
    }

    public function getTotalVisitors() {
        try {
            $stmt = $this->pdo->query("SELECT SUM(visit_count) as total FROM visitors");
            $result = $stmt->fetch();
            return $result ? intval($result['total']) : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTodayVisitors() {
        try {
            $today = date('Y-m-d');
            $stmt = $this->pdo->prepare("SELECT visit_count FROM visitors WHERE visit_date = ?");
            $stmt->execute([$today]);
            $result = $stmt->fetch();
            return $result ? intval($result['visit_count']) : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    // ================= ADMIN =================

    public function verifyAdmin($username, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            return $user && password_verify($password, $user['password_hash']);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================= HERO SECTION =================

    public function getHeroSection() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM hero_section WHERE id = 1");
            $result = $stmt->fetch();

            if (!$result) {
                return [
                    'badge' => 'Melayani perlengkapan sembako',
                    'title1' => 'Toko',
                    'title2' => 'Daffa',
                    'subtitle' => 'Segala kebutuhan sembako tersedia lengkap.',
                    'open_time' => '07:00',
                    'close_time' => '21:30',
                    'background_image' => 'toko-daffa.png',
                    'button1_text' => 'Lihat Produk',
                    'button1_link' => '#produk',
                    'button2_text' => 'Kunjungi Toko',
                    'button2_link' => '#kontak'
                ];
            }

            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function updateHeroSection($data) {
        try {
            $sql = "UPDATE hero_section SET
                    badge = :badge,
                    title1 = :title1,
                    title2 = :title2,
                    subtitle = :subtitle,
                    open_time = :open_time,
                    close_time = :close_time,
                    background_image = :background_image,
                    button1_text = :button1_text,
                    button1_link = :button1_link,
                    button2_text = :button2_text,
                    button2_link = :button2_link,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = 1";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================= ABOUT SECTION =================

    public function getAboutSection() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM about_section WHERE id = 1");
            $result = $stmt->fetch();

            if (!$result) {
                return [
                    'title' => 'Tentang Toko Daffa',
                    'description' => 'Kami menyediakan berbagai kebutuhan sembako dengan harga terjangkau.',
                    'year_established' => '2015',
                    'customer_count' => '1000+',
                    'feature1' => 'Harga Terjangkau',
                    'feature2' => 'Pelayanan Ramah',
                    'feature2_note' => 'Siap membantu setiap hari',
                    'image' => 'tentang.png'
                ];
            }

            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function updateAboutSection($data) {
        try {
            $sql = "UPDATE about_section SET
                    title = :title,
                    description = :description,
                    year_established = :year_established,
                    customer_count = :customer_count,
                    feature1 = :feature1,
                    feature2 = :feature2,
                    feature2_note = :feature2_note,
                    image = :image,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = 1";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ================= CONTACT SECTION =================

    public function getContactSection() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM contact_section WHERE id = 1");
            $result = $stmt->fetch();

            if (!$result) {
                return [
                    'address' => 'Jl. Contoh No. 123, Kota, Provinsi 12345',
                    'whatsapp_number' => '6281234567890',
                    'whatsapp_display' => '+62 812-3456-7890',
                    'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.16259714688!2d106.77397639999999!3d-6.2293866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945f34dbf%3A0x5378bf6a9f1b3e6d!2sJakarta!5e0!3m2!1sen!2sid!4v1234567890',
                    'maps_link' => 'https://goo.gl/maps/example'
                ];
            }

            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function updateContactSection($data) {
        try {
            $check = $this->pdo->query("SELECT id FROM contact_section WHERE id = 1")->fetch();
            
            if ($check) {
                $sql = "UPDATE contact_section SET
                        address = :address,
                        whatsapp_number = :whatsapp_number,
                        whatsapp_display = :whatsapp_display,
                        maps_embed_url = :maps_embed_url,
                        maps_link = :maps_link,
                        updated_at = CURRENT_TIMESTAMP
                        WHERE id = 1";
            } else {
                $sql = "INSERT INTO contact_section 
                        (id, address, whatsapp_number, whatsapp_display, maps_embed_url, maps_link)
                        VALUES (1, :address, :whatsapp_number, :whatsapp_display, :maps_embed_url, :maps_link)";
            }

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            return false;
        }
    }
}