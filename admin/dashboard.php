<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Query untuk mendapatkan jumlah user
$stmt_users = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
$total_users = $stmt_users->fetch()['total_users'];

// Query untuk mendapatkan jumlah sepeda
$stmt_bikes = $pdo->query("SELECT COUNT(*) AS total_bikes FROM bikes");
$total_bikes = $stmt_bikes->fetch()['total_bikes'];

// Query untuk mendapatkan total penjualan
$stmt_sales = $pdo->query("SELECT SUM(total_price) AS total_sales FROM sales WHERE status = 'completed'");
$total_sales = $stmt_sales->fetch()['total_sales'];

// Query untuk mendapatkan penjualan per sepeda
$stmt_sales_data = $pdo->query("
    SELECT b.name AS bike_name, SUM(s.quantity) AS total_sales
    FROM sales s
    JOIN bikes b ON s.bike_id = b.id
    WHERE s.status = 'completed'
    GROUP BY b.name
");

$sales_data = $stmt_sales_data->fetchAll(PDO::FETCH_ASSOC);
$labels = [];
$data = [];
foreach ($sales_data as $row) {
    $labels[] = $row['bike_name'];
    $data[] = $row['total_sales'];
}

// Hitung total penjualan untuk mendapatkan persentase
$total_quantity = array_sum($data);
$percentage_data = [];
if ($total_quantity > 0) {
    foreach ($data as $value) {
        $percentage_data[] = ($value / $total_quantity) * 100; // Hitung persentase
    }
}

include '../includes/navbar_admin.php';
?>

<div class="container mt-4">
    <h1 class="text-center mb-5">Selamat Datang di Admin Panel</h1>

    <!-- Tiga Card di bagian atas -->
    <div class="row">
        <!-- Card Jumlah User -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-primary font-weight-bold" style="font-size: 1.25rem;">Jumlah User</h5>
                    <p class="card-text" style="font-size: 2rem; font-weight: bold;"><?= $total_users ?> Users</p>
                </div>
            </div>
        </div>

        <!-- Card Jumlah Sepeda -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-success font-weight-bold" style="font-size: 1.25rem;">Jumlah Jenis Sepeda</h5>
                    <p class="card-text" style="font-size: 2rem; font-weight: bold;"><?= $total_bikes ?> Sepeda</p>
                </div>
            </div>
        </div>

        <!-- Card Total Penjualan -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-info font-weight-bold" style="font-size: 1.25rem;">Total Penjualan</h5>
                    <p class="card-text" style="font-size: 2rem; font-weight: bold;">Rp <?= number_format($total_sales, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>


    <!-- Chart Section -->
    <div class="row d-flex mt-5">
        <div class="col-md-6 mb-3">
            <div style="height: 300px;">
                <canvas id="barChart" class="rounded shadow-sm"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-3 ms-auto">
            <div style="height: 300px;">
                <canvas id="pieChart" class="mx-auto rounded shadow-sm"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Data untuk bar chart
    const ctxBar = document.getElementById('barChart').getContext('2d');
    const chartData = {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Penjualan per Sepeda',
            data: <?php echo json_encode($data); ?>,
            backgroundColor: [
                'rgba(192, 57, 43, 0.8)', // Merah Tua
                'rgba(39, 174, 96, 0.8)', // Hijau Tua
                'rgba(41, 128, 185, 0.8)', // Biru Tua
                'rgba(155, 89, 182, 0.8)', // Ungu Tua
                'rgba(241, 196, 15, 0.8)', // Kuning Tua
                'rgba(52, 152, 219, 0.8)' // Biru Muda
            ],
        }]
    };

    // Data untuk pie chart dengan persentase
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    const pieChartData = {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($percentage_data); ?>, // Menggunakan data persentase
            backgroundColor: [
                'rgba(192, 57, 43, 0.8)', // Merah Tua
                'rgba(39, 174, 96, 0.8)', // Hijau Tua
                'rgba(41, 128, 185, 0.8)', // Biru Tua
                'rgba(155, 89, 182, 0.8)', // Ungu Tua
                'rgba(241, 196, 15, 0.8)', // Kuning Tua
                'rgba(52, 152, 219, 0.8)' // Biru Muda
            ],
        }]
    };

    // Bar chart
    const barChart = new Chart(ctxBar, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true, // Chart responsif agar menyesuaikan ukuran layar
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Pie chart with percentage
    const pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: pieChartData,
        options: {
            responsive: true, // Agar chart responsif dengan ukuran layar
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            // Menghitung total dari semua data pada chart
                            const total = tooltipItem.dataset.data.reduce((acc, val) => acc + val, 0);
                            // Menghitung persentase berdasarkan nilai saat ini dan total
                            const percentage = ((tooltipItem.raw / total) * 100).toFixed(2);
                            // Menampilkan label dengan persentase
                            return tooltipItem.label + ': ' + percentage + '%';
                        }
                    }
                }
            }
        }
    });
</script>