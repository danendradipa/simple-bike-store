<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

// Query untuk mendapatkan jumlah user
$stmt_users = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
$total_users = $stmt_users->fetch()['total_users'];

// Query untuk mendapatkan jumlah sepeda
$stmt_bikes = $pdo->query("SELECT COUNT(*) AS total_bikes FROM bikes");
$total_bikes = $stmt_bikes->fetch()['total_bikes'];


include '../includes/navbar_admin.php';
?>

<div class="container mt-4">
    <h1 class="text-center mb-5">Selamat Datang di Admin Panel</h1>

    <!-- Tiga Card di bagian atas -->
    <div class="row">
        <!-- Card Jumlah User -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-primary font-weight-bold" style="font-size: 1.25rem;">Jumlah User</h5>
                    <p class="card-text" style="font-size: 2rem; font-weight: bold;"><?= $total_users ?> Users</p>
                </div>
            </div>
        </div>

        <!-- Card Jumlah Sepeda -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-success font-weight-bold" style="font-size: 1.25rem;">Jumlah Jenis Sepeda</h5>
                    <p class="card-text" style="font-size: 2rem; font-weight: bold;"><?= $total_bikes ?> Sepeda</p>
                </div>
            </div>
        </div>
    </div>