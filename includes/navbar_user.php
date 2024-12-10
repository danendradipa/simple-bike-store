<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toko Sepeda</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/sweetalert2.min.css">
  <style>
    .navbar-nav .nav-link.btn-danger {
      background-color: #dc3545 !important;
      /* Warna merah */
      color: white !important;
      padding: 6px 15px !important;      /* Warna teks putih */
    }

    .navbar-nav .nav-link.btn-primary {
      background-color: #007bff !important;
      /* Warna biru */
      color: white !important;
      padding: 6px 15px !important;
      /* Warna teks putih */
    }

    .chat-icon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #0A3981;
      /* Warna latar belakang */
      color: white;
      /* Warna ikon */
      border-radius: 50%;
      padding: 15px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      cursor: pointer;
      z-index: 1000;
      /* Agar ikon selalu di atas */
    }

    .wrapper {
      position: fixed;
      bottom: 80px;
      /* Jarak dari bawah */
      right: 20px;
      /* Jarak dari kanan */
      max-width: 450px;
      background: #fff;
      padding: 20px;
      box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      z-index: 1000;
      /* Agar jendela chatbot selalu di atas */
    }

    .title {
      background: #0A3981;
      color: #fff;
      padding: 10px 20px;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      border-radius: 10px 10px 0 0;
    }

    .form {
      max-height: 300px;
      overflow-y: auto;
      padding: 10px;
      background-color: #f9f9f9;
    }

    .inbox {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 10px 0;
    }

    .user-inbox .msg-header {
      background-color: #0A3981;
      color: #fff;
      padding: 10px 15px;
      border-radius: 20px 20px 0 20px;
      margin-left: auto;
    }

    .bot-inbox .msg-header {
      background-color: #f1f0f0;
      padding: 10px 15px;
      border-radius: 20px 20px 20px 0;
      color: #333;
    }

    .msg-header p {
      margin: 0;
    }
    
    .icon {
      width: 30px;
      height: 30px;
      background-color: #ddd;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      margin-right: 10px;
    }

    .typing-field {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }

    .input-data {
      flex: 1;
      display: flex;
    }

    .input-data input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 20px;
      outline: none;
      font-size: 16px;
    }

    #send-btn {
      padding: 10px 20px;
      background-color: #0A3981;
      color: #fff;
      border: none;
      border-radius: 20px;
      margin-left: 10px;
      cursor: pointer;
    }

    #send-btn:hover {
      background-color: #476dd0;
    }

    .form::-webkit-scrollbar {
      width: 5px;
    }

    .form::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }

    .form::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
  </style>
</head>



<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <div class="container">
      <a class="navbar-brand" href="index.php">Toko Sepeda</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto d-flex align-items-center">
          <?php if (isset($_SESSION['user_name'])): ?>
            <li class="nav-item me-5"> <!-- Menggunakan me-5 untuk margin end -->
              <a class="nav-link text-white d-flex flex-column" href="#">
                <span class="fw-bold">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <span class="small text-center">(<?= htmlspecialchars($_SESSION['role']) ?>)</span>
              </a>
            </li>
          <?php endif; ?>

          <!-- Tautan yang selalu ditampilkan -->
          <li class="nav-item me-3"> <!-- Menggunakan me-3 untuk margin end -->
            <a class="nav-link text-white" href="index.php">Daftar Sepeda</a>
          </li>

          <li class="nav-item me-3"> <!-- Menggunakan me-3 untuk margin end -->
            <a class="nav-link text-white" href="faq.php">FAQ</a>
          </li>

          <?php if (isset($_SESSION['user_name'])): ?>
            <!-- Jika login, tampilkan tautan tambahan -->
            <li class="nav-item me-3"> <!-- Menggunakan me-3 untuk margin end -->
              <a class="nav-link text-white" href="buy_status.php">Status Transaksi</a>
            </li>
            <!-- Jika login, tampilkan tombol Logout -->
            <li class="nav-item">
              <a class="nav-link btn btn-danger" href="logout.php" onclick="confirmLogout(event)">Logout</a>
            </li>
          <?php else: ?>
            <!-- Jika tidak login, tampilkan tombol Login -->
            <li class="nav-item">
              <a class="nav-link btn btn-primary" href="login.php">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Floating Chatbot Icon -->
  <div class="chat-icon" id="chat-icon">
    <i class="fas fa-comments"></i>
  </div>

  <!-- Chatbot Window -->
  <div class="wrapper" id="chatbot" style="display: none;">
    <div class="title">Chatbot</div>
    <div class="form">
      <!-- Placeholder awal yang akan digantikan dengan daftar perintah -->
      <div class="bot-inbox inbox">
        <div class="icon">
          <i class="fas fa-user"></i>
        </div>
        <div class="msg-header">
          <p>Sedang memuat daftar perintah...</p>
        </div>
      </div>
    </div>
    <div class="typing-field">
      <div class="input-data">
        <input id="data" type="text" placeholder="Type something here.." required>
        <button id="send-btn">Send</button>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.min.js"></script>
  <script src="js/all.min.js"></script>
  <script src="js/sweetalert2.all.min.js"></script>
  <script>
    // Mendapatkan elemen-elemen yang diperlukan
    const chatIcon = document.getElementById('chat-icon');
    const chatbot = document.getElementById('chatbot');
    const sendBtn = document.getElementById('send-btn');
    const inputData = document.getElementById('data');
    const form = document.querySelector('.form');

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
          window.location.href = 'logout.php';
        }
      });
    }

    // Fungsi untuk memuat daftar perintah dari chatbot_response.php
    function loadCommandList() {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'chatbot_response.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const data = JSON.parse(xhr.responseText);
          const commands = data.perintah || []; // Daftar perintah dari respons
          const commandList = commands.map(cmd => `- ${cmd}`).join('<br>'); // Format sebagai daftar
          const message = `
          Hai, perlu suatu bantuan? Jika iya gunakan perintah berikut ini:<br>
          ${commandList}
        `;
          // Perbarui msg-header dengan daftar perintah
          form.innerHTML = `
          <div class="bot-inbox inbox">
            <div class="icon">
              <i class="fas fa-user"></i>
            </div>
            <div class="msg-header">
              <p>${message}</p>
            </div>
          </div>
        `;
        }
      };
      xhr.send(); // Tidak perlu parameter untuk permintaan daftar perintah
    }

    // Menangani klik pada ikon chatbot untuk menampilkan/menyembunyikan jendela chatbot
    chatIcon.addEventListener('click', () => {
      const isHidden = chatbot.style.display === 'none';
      chatbot.style.display = isHidden ? 'block' : 'none';
      if (isHidden) loadCommandList(); // Muat daftar perintah saat chatbot dibuka
    });

    // Fungsi untuk mengirim pesan
    function sendMessage() {
        const userInput = inputData.value.trim();
        if (userInput) {
            // Tambahkan pesan pengguna ke jendela chatbot
            form.innerHTML += `
            <div class="user-inbox inbox">
                <div class="msg-header">
                    <p>${userInput}</p>
                </div>
            </div>
            `;
            inputData.value = ''; // Kosongkan input

            // Kirim permintaan ke chatbot_response.php
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'chatbot_response.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    // Tambahkan respons chatbot ke jendela
                    form.innerHTML += `
                    <div class="bot-inbox inbox">
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="msg-header">
                            <p>${data.jawaban}</p>
                        </div>
                    </div>
                    `;
                    // Scroll ke bawah untuk melihat pesan terbaru
                    form.scrollTop = form.scrollHeight;
                }
            };
            xhr.send('perintah=' + encodeURIComponent(userInput));
        }
    }

    // // Event listener untuk tombol "Send"
    sendBtn.addEventListener('click', sendMessage);

    // Event listener untuk mendeteksi penekanan tombol "Enter"
    inputData.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });
</script>

</body>

</html>