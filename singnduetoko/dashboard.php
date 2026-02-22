<?php
require_once 'config.php';
require_once 'database.php';
requireLogin();

$page_title = "Dashboard";

$db = Database::getInstance();
$products = $db->getProducts();
$product_count = count($products);

// Hitung jumlah produk berdasarkan status
$unggulan_count = 0;
$reguler_count = 0;

foreach ($products as $product) {
    if ($product['is_highlight'] == 'unggulan') {
        $unggulan_count++;
    } else {
        $reguler_count++;
    }
}

// Ambil data pengunjung
$weekly_visitors = $db->getWeeklyVisitors();
$total_visitors = $db->getTotalVisitors();
$today_visitors = $db->getTodayVisitors();

include 'partials/header.php';
include 'partials/sidebar.php';
include 'partials/logout_modal.php';
?>

<!-- Load Chart.js di sini -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Content Dashboard -->
<div class="flex-1 overflow-y-auto">
    <!-- Content -->
    <div class="p-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-2xl shadow-lg p-6 text-white mb-8">
            <h3 class="text-xl font-bold mb-2">Selamat datang, Admin!</h3>
            <p class="text-green-100">Kelola konten website Toko Daffa dengan mudah melalui panel ini.</p>
        </div>
        
        <!-- Stat Cards - Baris 1 (Produk) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Produk -->
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $product_count; ?></p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i> Aktif
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="fas fa-box text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Produk Unggulan -->
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Produk Unggulan</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $unggulan_count; ?></p>
                        <p class="text-xs text-yellow-600 mt-2">
                            <i class="fas fa-star mr-1"></i> Prioritas
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-xl">
                        <i class="fas fa-star text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Produk Reguler -->
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Produk Reguler</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $reguler_count; ?></p>
                        <p class="text-xs text-blue-600 mt-2">
                            <i class="fas fa-tag mr-1"></i> Standar
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="fas fa-tag text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stat Cards - Baris 2 (Pengunjung) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Pengunjung -->
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-purple-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Pengunjung</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo number_format($total_visitors); ?></p>
                        <p class="text-xs text-purple-600 mt-2">
                            <i class="fas fa-users mr-1"></i> Semua waktu
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <i class="fas fa-users text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Pengunjung Hari Ini -->
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Pengunjung Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo number_format($today_visitors); ?></p>
                        <p class="text-xs text-orange-600 mt-2">
                            <i class="fas fa-calendar-day mr-1"></i> <?php echo date('d M Y'); ?>
                        </p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <i class="fas fa-eye text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Rata-rata per Hari -->
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border-l-4 border-teal-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Rata-rata / Hari</p>
                        <?php $avg = $total_visitors > 0 ? round($total_visitors / 7) : 0; ?>
                        <p class="text-3xl font-bold text-gray-800"><?php echo number_format($avg); ?></p>
                        <p class="text-xs text-teal-600 mt-2">
                            <i class="fas fa-chart-line mr-1"></i> 7 hari terakhir
                        </p>
                    </div>
                    <div class="bg-teal-100 p-3 rounded-xl">
                        <i class="fas fa-chart-bar text-2xl text-teal-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grafik Pengunjung dan Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Grafik Pengunjung -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-green-600"></i>
                    Grafik Pengunjung 7 Hari Terakhir
                </h3>
                
                <!-- Canvas untuk Chart.js -->
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="visitorChart"></canvas>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const canvas = document.getElementById('visitorChart');
                        if (!canvas) return;
                        
                        // Data dari PHP
                        const labels = <?php echo json_encode($weekly_visitors['labels']); ?>;
                        const data = <?php echo json_encode($weekly_visitors['data']); ?>;
                        
                        // Hancurkan chart lama jika ada
                        if (window.myChart) {
                            window.myChart.destroy();
                        }
                        
                        const ctx = canvas.getContext('2d');
                        
                        window.myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Pengunjung',
                                    data: data,
                                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                                    borderColor: '#16a34a',
                                    borderWidth: 3,
                                    tension: 0.3,
                                    fill: true,
                                    pointBackgroundColor: '#16a34a',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 5,
                                    pointHoverRadius: 7
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: '#1f2937',
                                        titleColor: '#f3f4f6',
                                        bodyColor: '#d1d5db',
                                        borderColor: '#16a34a',
                                        borderWidth: 1
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.05)'
                                        },
                                        ticks: {
                                            stepSize: 1,
                                            precision: 0
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
                
                <!-- Legend -->
                <div class="flex justify-between mt-4 text-sm text-gray-600">
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-600 rounded-full"></span>
                        <span>Total: <?php echo array_sum($weekly_visitors['data']); ?> pengunjung</span>
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-calendar text-green-600"></i>
                        <span>Minggu ini</span>
                    </span>
                </div>
            </div>
            
            <!-- Info & Statistik -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-green-600"></i>
                    Ringkasan Produk
                </h3>
                
                <!-- Progress Bar -->
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Produk Unggulan</span>
                            <span class="font-medium text-yellow-600"><?php echo $unggulan_count; ?> produk</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <?php $unggulan_width = ($product_count > 0) ? ($unggulan_count / $product_count) * 100 : 0; ?>
                            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: <?php echo $unggulan_width; ?>%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Produk Reguler</span>
                            <span class="font-medium text-blue-600"><?php echo $reguler_count; ?> produk</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <?php $reguler_width = ($product_count > 0) ? ($reguler_count / $product_count) * 100 : 0; ?>
                            <div class="bg-blue-500 h-2.5 rounded-full" style="width: <?php echo $reguler_width; ?>%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Info Tambahan Produk -->
                <div class="mt-6 text-sm text-gray-600 space-y-2 border-t pt-4">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-circle text-green-500 text-xs"></i>
                        <span>Total produk: <strong><?php echo $product_count; ?></strong></span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-circle text-yellow-500 text-xs"></i>
                        <span>Unggulan: <strong><?php echo $unggulan_count; ?></strong> produk (<?php echo round($unggulan_width); ?>%)</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-circle text-blue-500 text-xs"></i>
                        <span>Reguler: <strong><?php echo $reguler_count; ?></strong> produk (<?php echo round($reguler_width); ?>%)</span>
                    </p>
                </div>
                
                <!-- Info Pengunjung -->
                <div class="mt-4 text-sm text-gray-600 space-y-2 border-t pt-4">
                    <h4 class="font-medium text-gray-700 mb-2">Detail Pengunjung 7 Hari:</h4>
                    <?php 
                    $total_week = 0;
                    foreach ($weekly_visitors['labels'] as $index => $day): 
                        $count = $weekly_visitors['data'][$index];
                        $total_week += $count;
                    ?>
                        <p class="flex items-center gap-2">
                            <i class="fas fa-circle text-green-500 text-xs"></i>
                            <span class="w-20"><?php echo $day; ?>:</span>
                            <strong><?php echo $count; ?></strong> pengunjung
                            <?php if ($count > 0): ?>
                                <span class="text-xs text-gray-400">(<?php echo round(($count / max($total_week, 1)) * 100); ?>%)</span>
                            <?php endif; ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>