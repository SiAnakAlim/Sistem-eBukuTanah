<?php
session_start();
require 'koneksi.php';

$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);

// Cek cookie remember me
$email = $_COOKIE['email'] ?? "";
$password = "";

// Jika form dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        if ($user['verifikasi'] == 0) {
            $_SESSION['email_verifikasi'] = $email;
            header('Location: verifikasi_akun.php');
            exit;
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            // Simpan cookie hanya untuk email (bukan password)
            if ($remember) {
                setcookie('email', $email, time() + (86400 * 30), "/", "", false, true);
            } else {
                setcookie('email', '', time() - 3600, "/");
            }

            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Password salah!";
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan!";
    }
    header('Location: login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
     <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />
    <style>
        body {
            background: linear-gradient(135deg, #d1d1d1, #f4f4f9);
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            width: 450px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            padding: 30px;
            text-align: center;
        }
        .login-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 16px;
            padding: 10px;
        }
        .form-control:focus {
            border-color: #e6b800;
            box-shadow: 0 0 5px rgba(230, 184, 0, 0.8);
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            cursor: pointer;
            color: #888;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .toggle-password:hover {
            color: #555;
        }
        .btn-login {
            background-color: #ffcc00;
            color: #333;
            border: none;
            font-weight: bold;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #e6b800;
            transform: scale(1.05);
        }
        .alert {
            font-size: 15px;
            padding: 12px;
        }
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 25px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
        .login-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo-container img {
            width: 100px;
            height: auto;
        }
        .btn-login {
            background-color: #ffcc00;
            color: #333;
            border: none;
            font-weight: bold;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #e6b800;
            transform: scale(1.05);
        }
        .text-muted {
            font-size: 14px;
        }
        .error-message {
            color: red;
            font-size: 12px;
        }
        .password-container {
            position: relative;
        }
        .password-container input {
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            bottom: 11px;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="logo-container">
        <img src="assets/img/logo-bpn.png" alt="Logo BPN">
    </div>
    <h2 class="login-title">Aplikasi Peminjaman</h2>

    <!-- Notifikasi error -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" 
                   placeholder="Masukkan email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
            </div>
        </div>
        <div class="mb-3">
            <input type="checkbox" name="remember" id="remember" <?php echo !empty($email) ? "checked" : ""; ?>>
            <label for="remember">Remember Me</label>
        </div>
        <div class="mb-3 text-start">
            <a href="forgot_password.php">Lupa Password?</a>
        </div>
        <button type="submit" class="btn btn-login w-100">Login</button>
    </form>

    <div class="text-center mt-3 text-muted">
        Belum punya akun? <a href="register.php">Daftar di sini</a>
    </div>
</div>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            var passwordField = document.getElementById("password");
            var icon = this;
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    </script>
</body>
</html>
