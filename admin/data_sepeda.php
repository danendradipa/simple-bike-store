<?php
include '../db.php';
include '../config.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Ambil semua data sepeda
$stmt = $pdo->prepare("SELECT * FROM bikes");
$stmt->execute();
$bikes = $stmt->fetchAll();

include '../includes/navbar_admin.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Data Sepeda</h1>
    <a href="add_bike.php" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Sepeda
    </a>
    <?php if (count($bikes) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Harga Sewa</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bikes as $bike): ?>
                    <tr>
                        <td><?= htmlspecialchars($bike['name']) ?></td>
                        <td><?= htmlspecialchars($bike['description']) ?></td>
                        <td>Rp <?= number_format($bike['price'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($bike['stock']) ?></td>
                        <td>
                            <?php if ($bike['image']): ?>
                                <img src="../assets/images/<?= htmlspecialchars($bike['image']) ?>" alt="<?= htmlspecialchars($bike['name']) ?>" class="img-thumbnail" style="width: 100px; height: auto;">
                            <?php else: ?>
                                <span class="text-muted">Tidak ada gambar</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_bike.php?id=<?= $bike['id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete_bike.php?id=<?= $bike['id'] ?>" class="btn btn-danger btn-sm mt-3" onclick="return confirm('Hapus sepeda ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Belum ada data sepeda.</div>
    <?php endif; ?>
</div>
