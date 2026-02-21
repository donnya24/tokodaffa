<?php
// singnduetoko/database.php

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {

            // Ambil dari .env (bukan SUPABASE_SERVICE_KEY)
            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $db   = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];

            // DSN untuk Supabase Pooler
            $dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode=require";

            if (APP_ENV === 'development') {
                error_log("Connecting to Supabase Pooler...");
                error_log("DSN: " . $dsn);
                error_log("User: " . $user);
            }

            $this->pdo = new PDO($dsn, $user, $pass);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_TIMEOUT, 30);

            if (APP_ENV === 'development') {
                error_log("Supabase connection SUCCESS");
            }

        } catch (PDOException $e) {

            error_log("Database connection error: " . $e->getMessage());

            if (APP_ENV === 'development') {
                die("
                    <h3>Database Connection Failed</h3>
                    Error: {$e->getMessage()} <br>
                    Code: {$e->getCode()}
                ");
            } else {
                die("Database connection failed.");
            }
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
            error_log("Test connection failed: " . $e->getMessage());
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
            error_log("getProducts error: " . $e->getMessage());
            return [];
        }
    }

    public function getProduct($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("getProduct error: " . $e->getMessage());
            return null;
        }
    }

    public function createProduct($data) {
        try {
            $sql = "INSERT INTO products 
                    (name, price, unit, description, image, is_highlight)
                    VALUES (:name, :price, :unit, :description, :image, :is_highlight)
                    RETURNING id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("createProduct error: " . $e->getMessage());
            return false;
        }
    }

    public function updateProduct($id, $data) {
        try {
            $data['id'] = $id;

            $sql = "UPDATE products SET
                    name = :name,
                    price = :price,
                    unit = :unit,
                    description = :description,
                    image = :image,
                    is_highlight = :is_highlight,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);

        } catch (PDOException $e) {
            error_log("updateProduct error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProduct($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("deleteProduct error: " . $e->getMessage());
            return false;
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
            error_log("verifyAdmin error: " . $e->getMessage());
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
            error_log("getHeroSection error: " . $e->getMessage());
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
            error_log("updateHeroSection error: " . $e->getMessage());
            return false;
        }
    }

    // ================= ABOUT SECTION =================

    public function getAboutSection() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM about_section WHERE id = 1");
            $result = $stmt->fetch();

            // Jika belum ada data di database
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
            error_log("getAboutSection error: " . $e->getMessage());
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
            error_log("updateAboutSection error: " . $e->getMessage());
            return false;
        }
    }

    // ================= CONTACT SECTION =================

    public function getContactSection() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM contact_section WHERE id = 1");
            $result = $stmt->fetch();

            // Jika belum ada data di database
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
            error_log("getContactSection error: " . $e->getMessage());
            
            // Return default values if table doesn't exist
            if (APP_ENV === 'development') {
                error_log("Contact section table might not exist. Returning default values.");
            }
            
            return [
                'address' => 'Jl. Contoh No. 123, Kota, Provinsi 12345',
                'whatsapp_number' => '6281234567890',
                'whatsapp_display' => '+62 812-3456-7890',
                'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.16259714688!2d106.77397639999999!3d-6.2293866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945f34dbf%3A0x5378bf6a9f1b3e6d!2sJakarta!5e0!3m2!1sen!2sid!4v1234567890',
                'maps_link' => 'https://goo.gl/maps/example'
            ];
        }
    }

    public function updateContactSection($data) {
        try {
            // Cek apakah data sudah ada
            $check = $this->pdo->query("SELECT id FROM contact_section WHERE id = 1")->fetch();
            
            if ($check) {
                // Update data yang sudah ada
                $sql = "UPDATE contact_section SET
                        address = :address,
                        whatsapp_number = :whatsapp_number,
                        whatsapp_display = :whatsapp_display,
                        maps_embed_url = :maps_embed_url,
                        maps_link = :maps_link,
                        updated_at = CURRENT_TIMESTAMP
                        WHERE id = 1";
            } else {
                // Insert data baru jika belum ada
                $sql = "INSERT INTO contact_section 
                        (id, address, whatsapp_number, whatsapp_display, maps_embed_url, maps_link)
                        VALUES (1, :address, :whatsapp_number, :whatsapp_display, :maps_embed_url, :maps_link)";
            }

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);

        } catch (PDOException $e) {
            error_log("updateContactSection error: " . $e->getMessage());
            return false;
        }
    }
}