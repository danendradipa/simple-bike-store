<?php
include 'db.php';
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['username_or_email']; // Ini akan digunakan untuk mencari username atau email
    $password = $_POST['password'];

    // Query untuk mendapatkan data pengguna berdasarkan username atau email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Mulai sesi jika belum dimulai
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Simpan data ke dalam sesi
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan peran pengguna
        if ($user['role'] === 'admin') {
            header("Location: ./admin/dashboard_admin.php");
        } elseif ($user['role'] === 'manager') {
            header("Location: ./manager/dashboard_manager.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Username atau email dan password salah.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            margin: auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
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

        .btn-login {
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

        .btn-login:hover {
            background: #2d3748;
            color: #fff;
            transform: translateY(-1px);
        }

        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .register-link a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            color: #2d3748;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 55%;
            cursor: pointer;
            color: #4a5568;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Selamat Datang</h2>
            <p class="text-muted">Login ke Akun Kamu</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username_or_email">Username atau Email</label>
                <input type="text" class="form-control" id="username_or_email" name="username_or_email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
        </form>

        <div class="register-link">
            <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
        </div>
    </div>

    <script src="js/bootstrap.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>