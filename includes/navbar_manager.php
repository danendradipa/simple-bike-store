<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Panel</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/all.min.css">
  <link rel="stylesheet" href="../css/sweetalert2.min.css">

  <style>
    .navbar-nav .nav-link.btn-danger {
      background-color: #dc3545 !important;
      /* Warna merah */
      color: white !important;
      /* Warna teks putih */
      padding: 6px 15px !important;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <div class="container">
      <a class="navbar-brand" href="dashboard_manager.php">Manager Panel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto d-flex align-items-center"> <!-- Mengganti ml-auto dengan ms-auto untuk Bootstrap 5 -->
          <li class="nav-item me-5"> <!-- Menggunakan me-5 untuk margin end -->
            <a class="nav-link text-white d-flex flex-column" href="#">
              <span class="fw-bold">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
              <span class="small text-center">(<?= htmlspecialchars($_SESSION['role']) ?>)</span>
            </a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link text-white" href="dashboard_manager.php">Dashboard</a> <!-- Menambahkan kelas text-white -->
          </li>
          <li class="nav-item me-3">
            <a class="nav-link text-white" href="view_sales.php">Daftar Transaksi</a> <!-- Menambahkan kelas text-white -->
          </li>
          <li class="nav-item">
            <a class="nav-link text-white btn btn-danger" href="../logout.php" onclick="confirmLogout(event)">Logout</a> <!-- Menambahkan kelas text-white -->
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/chart.umd.js"></script>
  <script src="../js/sweetalert2.all.min.js"></script>
  <script>
    function confirmLogout(event) {
      event.preventDefault(); // Mencegah tautan default

      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda akan keluar dari akun Anda!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Tidak'
      }).then((result) => {
        if (result.isConfirmed) {
          // Jika pengguna mengkonfirmasi, redirect ke halaman logout
          window.location.href = '../logout.php';
        }
      });
    }
  </script>
</body>

</html>