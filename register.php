<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Validasi password minimal 8 karakter
  if (strlen($password) < 8) {
    $error = "Password harus minimal 8 karakter.";
  } elseif ($password !== $confirm_password) {
    $error = "Password dan konfirmasi password tidak sesuai.";
  } else {
    // Cek apakah username atau email sudah terdaftar
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
      if ($existingUser['username'] === $username) {
        $error = "Username sudah digunakan.";
      } elseif ($existingUser['email'] === $email) {
        $error = "Email sudah terdaftar.";
      }
    } else {
      // Jika validasi lolos, simpan ke database
      $hashed_password = password_hash($password, PASSWORD_BCRYPT);
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->execute([$username, $email, $hashed_password]);

      header("Location: register.php");
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/sweetalert2.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
    }

    .register-container {
      max-width: 500px;
      width: 100%;
      margin: auto;
      padding: 2rem;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .register-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .register-header h2 {
      color: #2d3748;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-control {
      padding: 0.8rem;
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      background: #f8fafc;
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
      border-color: #4299e1;
    }

    .btn-register {
      width: 100%;
      padding: 0.8rem;
      background: #4a5568;
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: 600;
      margin-top: 1rem;
      transition: all 0.3s ease;
    }

    .btn-register:hover {
      background: #2d3748;
      color: #fff;
      transform: translateY(-1px);
    }

    .login-link {
      text-align: center;
      margin-top: 1.5rem;
    }

    .login-link a {
      color: #4a5568;
      text-decoration: none;
      font-weight: 500;
    }

    .login-link a:hover {
      color: #2d3748;
    }

    .password-toggle {
      position: absolute;
      right: 10px;
      top: 40px;
      cursor: pointer;
      color: #4a5568;
    }

    .password-requirements {
      font-size: 0.85rem;
      color: #718096;
      margin-top: 0.5rem;
    }

    .error-message {
      background: #fed7d7;
      color: #c53030;
      padding: 0.8rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="register-container">
    <div class="register-header">
      <h2>Buat Akun</h2>
      <p class="text-muted">Bergabung Sekarang!</p>
    </div>

    <?php if (isset($error)): ?>
      <div class="error-message">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form method="POST" id="registerForm">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
        <small class="text-muted">Password harus minimal 8 karakter.</small>
      </div>
      <div class="form-group">
        <label for="confirm_password">Konfirmasi Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
      </div>
      <button type="submit" class="btn btn-register" onclick="register()">
        <i class="fas fa-user-plus me-2"></i> Daftar
      </button>
    </form>

    <div class="login-link">
      <p>Sudah punya akun? <a href="login.php">Masuk Sekarang</a></p>
    </div>
  </div>

  <script src="js/sweetalert2.all.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const password = document.getElementById('password');
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
      const confirmPassword = document.getElementById('confirm_password');
      const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPassword.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    document.addEventListener('DOMContentLoaded', (event) => {
      // Cek apakah ada pesan error di halaman
      const errorMessage = document.querySelector('.error-message');

      // Cek apakah ada pesan sukses di session storage dan tidak ada pesan error
      if (sessionStorage.getItem('registrationSuccess') && !errorMessage) {
        Swal.fire({
          title: "Registrasi",
          text: "Anda Berhasil Registrasi, Silahkan Login",
          icon: "success"
        });
        // Hapus pesan sukses setelah ditampilkan
        sessionStorage.removeItem('registrationSuccess');
      }
    });

    // Simpan status registrasi di session storage sebelum form disubmit
    document.getElementById('registerForm').onsubmit = function() {
      sessionStorage.setItem('registrationSuccess', 'true');
    };
  </script>
</body>


</html>