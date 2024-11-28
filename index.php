<?php
include './db.php';
include './config.php';

// Pastikan sesi hanya dimulai sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil daftar sepeda dari database
$stmt = $pdo->prepare("SELECT * FROM bikes");
$stmt->execute();
$bikes = $stmt->fetchAll();

// Ambil nama pengguna dari sesi jika login
$user_name = $_SESSION['user_name'] ?? null;

include './includes/navbar_user.php';
?>

<div class="container mt-5">
  <h1 class="text-center mb-4">Daftar Sepeda</h1>
  <div class="row">
    <?php foreach ($bikes as $bike): ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm" style="height: 430px;">
          <img src="./assets/images/<?= htmlspecialchars($bike['image']) ?>" alt="<?= htmlspecialchars($bike['name']) ?>" class="card-img-top" style="height: 200px; object-fit: contain;">
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <h5 class="card-title"><?= htmlspecialchars($bike['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($bike['description']) ?></p>
            </div>
            <div>
              <p class="card-text fw-bold font-weight-bold fs-5">Rp <?= number_format($bike['price'], 2) ?></p>
              <?php if ($user_name): ?>
                <!-- Jika user login, link langsung ke buy_bike -->
                <a href="buy_bike.php?id=<?= $bike['id'] ?>" class="btn btn-success w-100">Beli Sekarang</a>
              <?php else: ?>
                <!-- Jika user belum login, arahkan ke login -->
                <a href="./login.php" class="btn btn-success w-100" onclick="alert('Anda belum login. Silakan login terlebih dahulu.')">Beli Sekarang</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
