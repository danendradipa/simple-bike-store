<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
  header("Location: ../login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $perintah = $_POST['perintah'];
  $jawaban = $_POST['jawaban'];

  $stmt = $pdo->prepare("INSERT INTO chatbot (perintah, jawaban) VALUES (?, ?)");
  $stmt->execute([$perintah, $jawaban]);

  header("Location: view_chatbot.php?add_success=true");
  exit();
}
include '../includes/navbar_admin.php';
?>

<div class="container mt-5">
  <h2 class="mb-4">Tambah Chatbot</h2>
  <form method="POST" id="addChatbot">
    <div class="mb-3">
      <label for="perintah" class="form-label">Perintah</label>
      <input type="text" class="form-control" id="perintah" name="perintah" required>
    </div>
    <div class="mb-3">
      <label for="jawaban" class="form-label">Jawaban</label>
      <textarea class="form-control" id="jawaban" name="jawaban" rows="3" required></textarea>
    </div>
    <button type="button" class="btn btn-primary" onclick="addChatbot()">Simpan</button>
    <a href="view_chatbot.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>

<script>
    function addChatbot() {
        // Ambil elemen form
        const form = document.getElementById('addChatbot');

        // Cek apakah form valid
        if (form.checkValidity()) {
            Swal.fire({
                title: 'Tambah Chatbot',
                text: "Apakah Anda yakin ingin menambahkan chatbot ini?",
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