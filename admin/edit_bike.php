<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM bikes WHERE id = ?");
$stmt->execute([$id]);
$bike = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_FILES['image']['name'];

    // Jika ada file baru diupload
    if ($image) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        $stmt = $pdo->prepare("UPDATE bikes SET name = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $stock, $image, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE bikes SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $stock, $id]);
    }

    header("Location: dashboard.php");
    exit();
}

include '../includes/navbar_admin.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Edit Data Sepeda</h1>
    <form method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-light">
        <!-- Input Nama Sepeda -->
        <div class="mb-3">
            <label for="name" class="form-label">Nama Sepeda</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($bike['name']) ?>" required>
        </div>

        <!-- Input Deskripsi Sepeda -->
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="4" required><?= htmlspecialchars($bike['description']) ?></textarea>
        </div>

        <!-- Input Harga Sewa -->
        <div class="mb-3">
            <label for="price" class="form-label">Harga Sewa (IDR)</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?= htmlspecialchars($bike['price']) ?>" required>
        </div>

        <!-- Input Stok -->
        <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" id="stock" name="stock" class="form-control" value="<?= htmlspecialchars($bike['stock']) ?>" required>
        </div>

        <!-- Input Gambar -->
        <div class="mb-3">
            <label for="image" class="form-label">Gambar Sepeda (opsional)</label>
            <?php if ($bike['image']): ?>
                <div class="mb-2">
                    <img src="../assets/images/<?= htmlspecialchars($bike['image']) ?>" alt="<?= htmlspecialchars($bike['name']) ?>" class="img-thumbnail" width="150">
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" class="form-control">
        </div>

        <!-- Tombol Simpan -->
        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="data_sepeda.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
        </div>
    </form>
</div>
