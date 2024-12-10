<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
  header("Location: ../login.php");
  exit();
}

// Ambil data dari tabel chatbot
$stmt = $pdo->query("SELECT * FROM chatbot");
$chatbots = $stmt->fetchAll();

include '../includes/navbar_admin.php';

?>

<div class="container mt-5">
  <h1 class="mb-4">Kelola Chatbot</h1>
  <a href="add_chatbot.php" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Tambah Chatbot
  </a>
  <?php if (count($chatbots) > 0): ?>
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Perintah</th>
          <th>Jawaban</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($chatbots as $index => $chatbot): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($chatbot['perintah']) ?></td>
            <td><?= htmlspecialchars($chatbot['jawaban']) ?></td>
            <td class="d-flex justify-content-start">
              <a href="edit_chatbot.php?id=<?= $chatbot['id'] ?>" class="btn btn-warning btn-sm me-2">
                <i class="fas fa-edit"></i> Edit
              </a>
              <a href="delete_chatbot.php?id=<?= $chatbot['id'] ?>" class="btn btn-danger btn-sm" onclick="confirmDelete(event, '<?= $chatbot['id'] ?>')">
                <i class="fas fa-trash"></i> Hapus
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-warning">Belum ada data chatbot.</div>
  <?php endif; ?>
</div>

<script>
    function confirmDelete(event, chatbotId) {
        event.preventDefault(); // Mencegah link default agar tidak langsung diarahkan

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus sepeda ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengkonfirmasi, arahkan ke URL penghapusan
                window.location.href = 'delete_chatbot.php?id=' + chatbotId;
            }
        });
    }

    // Tampilkan alert sukses jika parameter success ada di URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('delete_success')) {
        Swal.fire({
            title: 'Sukses!',
            text: 'Data chatbot berhasil dihapus.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else if (urlParams.has('add_success')) {
        Swal.fire({
            title: 'Sukses!',
            text: 'Data chatbot berhasil ditambahkan.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else if (urlParams.has('edit_success')) {
        Swal.fire({
            title: 'Sukses!',
            text: 'Data chatbot berhasil diubah.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }
</script>