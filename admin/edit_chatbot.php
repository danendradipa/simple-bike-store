<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
  header("Location: ../login.php");
  exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM chatbot WHERE id = ?");
$stmt->execute([$id]);
$chatbot = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $perintah = $_POST['perintah'];
  $jawaban = $_POST['jawaban'];

  $stmt = $pdo->prepare("UPDATE chatbot SET perintah = ?, jawaban = ? WHERE id = ?");
  $stmt->execute([$perintah, $jawaban, $id]);

  header("Location: view_chatbot.php?edit_success=true");
  exit();
}
include '../includes/navbar_admin.php';
?>

<div class="container mt-5">
  <h2 class="mb-4">Edit Chatbot</h2>
  <form method="POST" id="editChatbot">
    <div class="mb-3">
      <label for="perintah" class="form-label">Perintah</label>
      <input type="text" class="form-control" id="perintah" name="perintah" value="<?= htmlspecialchars($chatbot['perintah']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="jawaban" class="form-label">Jawaban</label>
      <textarea class="form-control" id="jawaban" name="jawaban" rows="3" required><?= htmlspecialchars($chatbot['jawaban']) ?></textarea>
    </div>
    <button type="button" class="btn btn-primary" onclick="editChatbot()">Simpan</button>
    <a href="view_chatbot.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>

<script>
    function editChatbot() {
        // Ambil elemen form
        const form = document.getElementById('editChatbot');

        // Cek apakah form valid
        if (form.checkValidity()) {
            Swal.fire({
                title: 'Edit Chatbot',
                text: "Apakah Anda yakin ingin mengedit chatbot ini?",
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