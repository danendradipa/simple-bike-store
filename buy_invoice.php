<?php
include './db.php';
include './config.php';

if (!isLoggedIn()) {
  header("Location: ./login.php");
  exit();
}

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

// Ambil sale_id dari URL
$sale_id = isset($_GET['sale_id']) ? $_GET['sale_id'] : null;

// Jika sale_id tidak ada, redirect ke halaman index
if (!$sale_id) {
  header("Location: index.php");
  exit();
}

// Ambil detail pembelian berdasarkan sale_id
$stmt = $pdo->prepare("
    SELECT s.*, b.name AS bike_name, b.image AS bike_image, u.username AS user_name
    FROM sales s
    JOIN bikes b ON s.bike_id = b.id
    JOIN users u ON s.user_id = u.id
    WHERE s.id = ? AND s.user_id = ?"); // Tambahkan pengecekan user_id untuk memastikan hanya milik pengguna yang login
$stmt->execute([$sale_id, $user_id]);
$sale = $stmt->fetch();

// Jika transaksi tidak ditemukan atau bukan milik pengguna yang login
if (!$sale) {
  header("Location: index.php");
  exit();
}

include './includes/navbar_user.php';
?>


<div id="invoice" class="container mt-5">
  <h1 class="mb-4">Bukti Pembelian</h1>
  <div class="card">
    <div class="card-header">
      <h3>Detail Pembelian</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <img src="./assets/images/<?= htmlspecialchars($sale['bike_image']) ?>"
               alt="<?= htmlspecialchars($sale['bike_name']) ?>"
               class="img-fluid rounded">
        </div>
        <div class="col-md-8">
          <h4>Nama Sepeda: <?= htmlspecialchars($sale['bike_name']) ?></h4>
          <p><strong>ID Transaksi:</strong> <?= $sale['id'] ?></p>
          <p><strong>Nama Pembeli:</strong> <?= htmlspecialchars($sale['user_name']) ?></p>
          <p><strong>Tanggal Pembelian:</strong> <?= htmlspecialchars($sale['created_at']) ?></p>
          <p><strong>Jumlah:</strong> <?= $sale['quantity'] ?></p>
          <p><strong>Total Harga:</strong> Rp <?= number_format($sale['total_price'], 2, ',', '.') ?></p>
          <p><strong>Status:</strong> <?= htmlspecialchars($sale['status']) ?></p>
        </div>
      </div>
    </div>
    <div class="card-footer text-center no-print">
      <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
      <button id="printBtn" class="btn btn-success">Cetak Bukti</button>
    </div>
  </div>
</div>

<script>
  // Tampilkan alert sukses jika parameter success ada di URL
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('success')) {
    Swal.fire({
      title: 'Sukses!',
      text: 'Anda berhasil melakukan pembelian.',
      icon: 'success',
      confirmButtonText: 'OK'
    });
  }

  document.getElementById('printBtn').addEventListener('click', function () {
    // Ambil konten yang akan dicetak
    const printContent = document.getElementById('invoice').cloneNode(true);

    // Hapus elemen dengan kelas "no-print"
    printContent.querySelectorAll('.no-print').forEach(el => el.remove());

    // Buka jendela baru untuk mencetak
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
      <html>
        <head>
          <title>Bukti Pembelian</title>
          <link rel="stylesheet" href="css/bootstrap.min.css">
          <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .card { border: 1px solid #ddd; padding: 20px; }
            .card-header, .card-footer { background-color: #f8f9fa; }
          </style>
        </head>
        <body>
          ${printContent.outerHTML}
        </body>
      </html>
    `);

    // Fokuskan dan cetak halaman baru
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
  });
</script>