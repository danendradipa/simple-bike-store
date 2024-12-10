<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_FILES['image']['name'];

    // Upload gambar
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $stmt = $pdo->prepare("INSERT INTO bikes (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $image]);

        header("Location: data_sepeda.php?add_success=true");
        exit();
    } else {
        $error = "Gagal mengunggah gambar. Pastikan file valid.";
    }
}

include '../includes/navbar_admin.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Tambah Sepeda</h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="shadow p-4 bg-light rounded" id="addForm">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Sepeda</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama sepeda" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" placeholder="Tambahkan deskripsi sepeda" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga (Rp)</label>
            <input type="number" id="price" name="price" class="form-control" placeholder="Masukkan harga sepeda" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" id="stock" name="stock" class="form-control" placeholder="Masukkan jumlah stok sepeda" min="0" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Unggah Gambar</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
        </div>
        <button type="button" class="btn btn-primary" onclick="addBikes()">Tambah Sepeda</button>
        <a href="data_sepeda.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    function addBikes() {
        // Ambil elemen form
        const form = document.getElementById('addForm');

        // Cek apakah form valid
        if (form.checkValidity()) {
            Swal.fire({
                title: 'Tambah Sepeda',
                text: "Apakah Anda yakin ingin menambahkan sepeda ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tambahkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna mengkonfirmasi, submit formulir
                    form.submit();
                }
            });
        } else {
            // Jika form tidak valid, tampilkan pesan kesalahan
            form.reportValidity(); // Menampilkan pesan kesalahan untuk field yang tidak valid
        }
    }
</script>