<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Ambil data transaksi penjualan beserta detail pengguna dan sepeda
$stmt = $pdo->prepare("
    SELECT 
        sales.id, 
        users.username AS user_name, 
        bikes.name AS bike_name, 
        sales.sale_date, 
        sales.quantity, 
        sales.total_price, 
        sales.status
    FROM sales
    JOIN users ON sales.user_id = users.id
    JOIN bikes ON sales.bike_id = bikes.id
    ORDER BY sales.id DESC
");
$stmt->execute();
$sales = $stmt->fetchAll();

include '../includes/navbar_admin.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Daftar Penjualan</h1>
    <?php if (count($sales) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>Nama Sepeda</th>
                    <th>Tanggal Penjualan</th>
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
                        <td><?= htmlspecialchars($sale['sale_date']) ?></td>
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
    <?php else: ?>
        <div class="alert alert-warning">Belum ada transaksi penjualan.</div>
    <?php endif; ?>
</div>
