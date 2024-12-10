<?php
include '../db.php';
include '../config.php';

if (!isManager()) {
    header("Location: ../login.php");
    exit();
}

// Ambil data transaksi penjualan beserta detail pengguna dan sepeda
$stmt = $pdo->prepare("
    SELECT 
        sales.id, 
        users.username AS user_name, 
        bikes.name AS bike_name, 
        sales.created_at,
        sales.quantity, 
        sales.total_price, 
        sales.status
    FROM sales
    JOIN users ON sales.user_id = users.id
    JOIN bikes ON sales.bike_id = bikes.id
    ORDER BY created_at DESC
");

$stmt->execute();
$sales = $stmt->fetchAll();

include '../includes/navbar_manager.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Daftar Transaksi</h1>

    <!-- Tombol Cetak -->
    <div class="mb-3 text-end">
        <button id="printButton" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>

    <?php if (count($sales) > 0): ?>
        <div id="printArea">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Pengguna</th>
                        <th>Nama Sepeda</th>
                        <th>Tanggal Transaksi</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><?= htmlspecialchars($sale['id']) ?></td>
                            <td><?= htmlspecialchars($sale['user_name']) ?></td>
                            <td><?= htmlspecialchars($sale['bike_name']) ?></td>
                            <td><?= htmlspecialchars($sale['created_at']) ?></td>
                            <td><?= htmlspecialchars($sale['quantity']) ?></td>
                            <td>Rp <?= number_format($sale['total_price'], 2, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-<?= $sale['status'] === 'completed' ? 'success' : ($sale['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst($sale['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Belum ada transaksi penjualan.</div>
    <?php endif; ?>
</div>

<script>
    document.getElementById('printButton').addEventListener('click', function () {
        const printArea = document.getElementById('printArea').innerHTML;

        // Membuat window baru untuk mencetak
        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Cetak Daftar Transaksi</title>
                    <link rel="stylesheet" href="css/bootstrap.min.css">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            text-align: left;
                            padding: 8px;
                        }
                        th {
                            background-color: #343a40;
                            color: white;
                        }
                        tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }
                    </style>
                </head>
                <body>
                    <h1>Daftar Transaksi</h1>
                    ${printArea}
                </body>
            </html>
        `);

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
    });
</script>
