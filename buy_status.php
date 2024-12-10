<?php
include './db.php';
include './config.php';

if (!isLoggedIn()) {
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pembelian berdasarkan user_id
$stmt = $pdo->prepare("
    SELECT 
        sales.id, 
        bikes.name AS bike_name, 
        bikes.image, 
        sales.sale_date, 
        sales.quantity, 
        sales.total_price, 
        sales.status
    FROM sales
    JOIN bikes ON sales.bike_id = bikes.id
    WHERE sales.user_id = ?
    ORDER BY sales.id DESC
");
$stmt->execute([$user_id]);
$sales = $stmt->fetchAll();

include './includes/navbar_user.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Status Transaksi</h1>
    <?php if (count($sales) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID Transaksi</th>
                    <th>Gambar Sepeda</th>
                    <th>Nama Sepeda</th>
                    <th>Tanggal Transaksi</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Detail Pembelian</th> <!-- Kolom baru untuk link ke buy_invoice.php -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr class="text-center">
                        <td><?= htmlspecialchars($sale['id']) ?></td>
                        <td>
                            <img src="./assets/images/<?= htmlspecialchars($sale['image']) ?>" 
                                 alt="<?= htmlspecialchars($sale['bike_name']) ?>" 
                                 class="img-thumbnail" style="width: 100px;">
                        </td>
                        <td><?= htmlspecialchars($sale['bike_name']) ?></td>
                        <td><?= htmlspecialchars($sale['sale_date']) ?></td>
                        <td><?= htmlspecialchars($sale['quantity']) ?></td>
                        <td>Rp <?= number_format($sale['total_price'], 2, ',', '.') ?></td>
                        <td>
                            <span class="badge bg-<?= $sale['status'] === 'completed' ? 'success' : ($sale['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($sale['status']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <!-- Link menuju buy_invoice.php dengan parameter sale_id -->
                            <a href="buy_invoice.php?sale_id=<?= $sale['id'] ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Anda belum melakukan pembelian sepeda.</div>
    <?php endif; ?>
</div>
