<?php
include './db.php';
include './config.php';

if (!isLoggedIn()) {
    header("Location: ./login.php");
    exit();
}

$bike_id = $_GET['id'];

// Ambil detail sepeda
$stmt = $pdo->prepare("SELECT * FROM bikes WHERE id = ?");
$stmt->execute([$bike_id]);
$bike = $stmt->fetch();

if (!$bike) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Sepeda tidak ditemukan.</div></div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $quantity = (int)$_POST['quantity']; // Pastikan tipe data integer
    $sale_date = $_POST['sale_date'];

    // Validasi jumlah pembelian di server
    if ($quantity <= 0) {
        $error = "Jumlah pembelian tidak boleh kurang dari 1.";
    } elseif ($quantity > $bike['stock']) {
        $error = "Stok tidak mencukupi untuk jumlah yang diminta.";
    } else {
        $total_price = $quantity * $bike['price'];

        // Kurangi stok sepeda
        $stmt = $pdo->prepare("UPDATE bikes SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $bike_id]);

        // Catat penjualan
        $stmt = $pdo->prepare("
            INSERT INTO sales (user_id, bike_id, sale_date, quantity, total_price, status)
            VALUES (?, ?, ?, ?, ?, 'completed')
        ");
        $stmt->execute([$user_id, $bike_id, $sale_date, $quantity, $total_price]);

        header("Location: buy_status.php");
        exit();
    }
}

include './includes/navbar_user.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Beli Sepeda: <?= htmlspecialchars($bike['name']) ?></h1>
    <div class="row">
        <div class="col-md-6">
            <img src="./assets/images/<?= htmlspecialchars($bike['image']) ?>" alt="<?= htmlspecialchars($bike['name']) ?>" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h3>Harga: Rp <?= number_format($bike['price'], 2, ',', '.') ?></h3>
            <p><?= nl2br(htmlspecialchars($bike['description'])) ?></p>
            <p><strong>Stok Tersedia:</strong> <?= $bike['stock'] ?></p>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="sale_date" class="form-label">Tanggal Pembelian</label>
                    <input type="date" id="sale_date" name="sale_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Jumlah Pembelian</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" value="1" required>
                </div>
                <h4>Total: Rp <span id="total_price"><?= number_format($bike['price'], 2, ',', '.') ?></span></h4>
                <button type="submit" class="btn btn-success mt-3">Beli Sekarang</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Script untuk menghitung total harga secara dinamis
    document.getElementById('quantity').addEventListener('input', function() {
        const quantity = parseInt(this.value) || 0;
        const price = <?= $bike['price'] ?>;
        const total = quantity > 0 ? quantity * price : 0; // Pastikan total tidak negatif
        document.getElementById('total_price').innerText = total.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).replace('Rp', '').trim(); // Menghapus 'Rp ' yang sudah ada
    });
</script>