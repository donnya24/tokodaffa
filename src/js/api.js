// frontend/src/js/api.js
const API_URL = window.location.origin;

// Fungsi untuk mengambil produk dari database
async function getProducts() {
  try {
    const response = await fetch(`${API_URL}/api/products.php`);
    if (!response.ok) throw new Error("Failed to fetch");
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error loading products:", error);
    return [];
  }
}

// Fungsi untuk mengambil hero section
async function getHeroSection() {
  try {
    const response = await fetch(`${API_URL}/api/hero.php`);
    if (!response.ok) throw new Error("Failed to fetch");
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error loading hero:", error);
    return {
      badge: "Melayani perlengkapan sembako",
      title1: "Toko",
      title2: "Daffa",
      subtitle:
        "Segala kebutuhan sembako, bensin eceran + tabung gas LPG 3kg (melon). Harga ramah, pelayanan cepat.",
      open_time: "07:00",
      close_time: "21:30",
      background_image: "toko-daffa.png",
      button1_text: "Lihat produk",
      button1_link: "#produk",
      button2_text: "Kunjungi toko",
      button2_link: "#kontak",
    };
  }
}

// Fungsi untuk mengambil tentang kami
async function getTentangSection() {
  try {
    const response = await fetch(`${API_URL}/api/tentang.php`);
    if (!response.ok) throw new Error("Failed to fetch");
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error loading tentang:", error);
    return {
      title: "Toko Daffa",
      description:
        "Sejak 2015 melayani kebutuhan sembako masyarakat. Toko Daffa dikenal dengan kelengkapan barang, harga bersahabat, dan pelayanan ramah.",
      year_established: "2015",
      customer_count: "500+",
      feature1: "pelanggan tetap",
      feature2: "gratis antar",
      feature2_note: "hanya radius tertentu",
      image: "dalamtoko.png",
    };
  }
}

// Fungsi untuk mengambil kontak
async function getKontakSection() {
  try {
    const response = await fetch(`${API_URL}/api/kontak.php`);
    if (!response.ok) throw new Error("Failed to fetch");
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error loading kontak:", error);
    return {
      address:
        "Jl. Ke Ngluyu, Gondang Kulon, Gondangkulon, Kec. Gondang, Kabupaten Nganjuk, Jawa Timur 64451",
      whatsapp_number: "+6282264628643",
      whatsapp_display: "0822-6462-8643",
      maps_embed_url:
        "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.4883479273976!2d111.95391829219946!3d-7.52159063685561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7837faa376c42f%3A0xd992eb38538977cc!2sTOKO%20%22DAFFA%22!5e0!3m2!1sid!2sid!4v1771508713569!5m2!1sid!2sid",
      maps_link: "https://maps.app.goo.gl/w7oNywaz7Huiu6pVA",
    };
  }
}

// Export
window.tokoDaffaAPI = {
  getProducts,
  getHeroSection,
  getTentangSection,
  getKontakSection,
};
