<?php
// Menghubungkan ke database
include 'db.php';

// Pastikan permintaan menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Cek apakah ini permintaan daftar perintah
if (!isset($_POST['perintah'])) {
    // Ambil semua perintah dari database
    $stmt = $pdo->query("SELECT perintah FROM chatbot");
    $commands = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode(['perintah' => $commands]);
    exit();
}

// Logika respons berdasarkan perintah
$perintah = trim($_POST['perintah']);
$stmt = $pdo->prepare("SELECT jawaban FROM chatbot WHERE perintah = :perintah");
$stmt->bindParam(':perintah', $perintah, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $jawaban = $stmt->fetch(PDO::FETCH_ASSOC)['jawaban'];
    echo json_encode(['jawaban' => $jawaban]);
} else {
    echo json_encode(['jawaban' => 'Maaf, saya tidak mengerti perintah tersebut.']);
}
