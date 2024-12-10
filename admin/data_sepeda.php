<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
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
                            <a href="delete_bike.php?id=<?= $bike['id'] ?>" class="btn btn-danger btn-sm" onclick="confirmDelete(event, '<?= $bike['id'] ?>')">
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

<script>
    function confirmDelete(event, bikeId) {
        event.preventDefault(); // Mencegah link default agar tidak langsung diarahkan

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus sepeda ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengkonfirmasi, arahkan ke URL penghapusan
                window.location.href = 'delete_bike.php?id=' + bikeId;
            }
        });
    }

    // Tampilkan alert sukses jika parameter success ada di URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('delete_success')) {
        Swal.fire({
            title: 'Sukses!',
            text: 'Sepeda berhasil dihapus.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else if (urlParams.has('add_success')) {
        Swal.fire({
            title: 'Sukses!',
            text: 'Sepeda berhasil ditambahkan.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else if (urlParams.has('edit_success')) {
        Swal.fire({
            title: 'Sukses!',
            text: 'Data sepeda berhasil diubah.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }
</script>