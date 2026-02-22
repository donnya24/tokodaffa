// frontend/src/js/api.js
const API_URL = window.location.origin;

// Helper function dengan timeout
async function fetchWithTimeout(url, options = {}, timeout = 8000) {
  const controller = new AbortController();
  const id = setTimeout(() => controller.abort(), timeout);

  try {
    const response = await fetch(url, {
      ...options,
      signal: controller.signal,
    });
    clearTimeout(id);

    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    return await response.json();
  } catch (error) {
    clearTimeout(id);
    if (error.name === "AbortError") {
      console.warn(`Request timeout for ${url}`);
      return null;
    }
    throw error;
  }
}

// Fungsi untuk mengambil produk dari database
async function getProducts() {
  try {
    console.log("Fetching products...");
    const data = await fetchWithTimeout(
      `${API_URL}/api/products.php`,
      {},
      10000,
    );

    if (!data) {
      console.warn("Products fetch timeout");
      return [];
    }

    if (data.error) {
      console.error("API Error:", data.error);
      return [];
    }

    console.log(`Products loaded: ${data.length} items`);
    return data;
  } catch (error) {
    console.error("Error loading products:", error);
    return [];
  }
}

// Fungsi untuk mengambil hero section
async function getHeroSection() {
  try {
    const data = await fetchWithTimeout(`${API_URL}/api/hero.php`, {}, 5000);
    if (data && !data.error) return data;

    // Fallback default
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
  } catch (error) {
    console.error("Error loading hero:", error);
    return getDefaultHero();
  }
}

// Fungsi untuk mengambil tentang kami
async function getTentangSection() {
  try {
    const data = await fetchWithTimeout(`${API_URL}/api/tentang.php`, {}, 5000);
    if (data && !data.error) return data;

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
  } catch (error) {
    console.error("Error loading tentang:", error);
    return getDefaultTentang();
  }
}

// Fungsi untuk mengambil kontak
async function getKontakSection() {
  try {
    const data = await fetchWithTimeout(`${API_URL}/api/kontak.php`, {}, 5000);
    if (data && !data.error) return data;

    return {
      address:
        "Jl. Ke Ngluyu, Gondang Kulon, Gondangkulon, Kec. Gondang, Kabupaten Nganjuk, Jawa Timur 64451",
      whatsapp_number: "+6282264628643",
      whatsapp_display: "0822-6462-8643",
      maps_embed_url:
        "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.4883479273976!2d111.95391829219946!3d-7.52159063685561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7837faa376c42f%3A0xd992eb38538977cc!2sTOKO%20%22DAFFA%22!5e0!3m2!1sid!2sid!4v1771508713569!5m2!1sid!2sid",
      maps_link: "https://maps.app.goo.gl/w7oNywaz7Huiu6pVA",
    };
  } catch (error) {
    console.error("Error loading kontak:", error);
    return getDefaultKontak();
  }
}

// Default fallback functions
function getDefaultHero() {
  return {
    badge: "Melayani perlengkapan sembako",
    title1: "Toko",
    title2: "Daffa",
    subtitle: "Segala kebutuhan sembako, bensin eceran + tabung gas LPG 3kg",
    open_time: "07:00",
    close_time: "21:30",
    background_image: "toko-daffa.png",
    button1_text: "Lihat produk",
    button1_link: "#produk",
    button2_text: "Kunjungi toko",
    button2_link: "#kontak",
  };
}

function getDefaultTentang() {
  return {
    title: "Toko Daffa",
    description: "Melayani kebutuhan sembako masyarakat.",
    year_established: "2015",
    customer_count: "500+",
    feature1: "pelanggan tetap",
    feature2: "gratis antar",
    feature2_note: "syarat berlaku",
    image: "dalamtoko.png",
  };
}

function getDefaultKontak() {
  return {
    address:
      "Jl. Ke Ngluyu, Gondang Kulon, Gondangkulon, Kec. Gondang, Kabupaten Nganjuk",
    whatsapp_number: "+6282264628643",
    whatsapp_display: "0822-6462-8643",
    maps_embed_url:
      "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.4883479273976!2d111.95391829219946!3d-7.52159063685561",
    maps_link: "https://maps.app.goo.gl/w7oNywaz7Huiu6pVA",
  };
}

// Export ke window
window.tokoDaffaAPI = {
  getProducts,
  getHeroSection,
  getTentangSection,
  getKontakSection,
};

// Auto-load produk saat halaman dimuat (untuk testing)
document.addEventListener("DOMContentLoaded", () => {
  // Test koneksi API
  getProducts().then((products) => {
    if (products.length === 0) {
      console.warn("⚠️ No products loaded from API");
    } else {
      console.log(`✅ API OK: ${products.length} products ready`);
    }
  });
});
