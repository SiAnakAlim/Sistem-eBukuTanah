<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid!';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+?]).{8,20}$/', $password)) {
        $error = 'Password harus 8-20 karakter, mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 karakter spesial!';
    } elseif ($password !== $konfirmasi_password) {
        $error = 'Konfirmasi password tidak sesuai!';
    } else {
        // Ambil domain dari email
        $email_domain = substr(strrchr($email, "@"), 1);

        // Cek apakah domain email memiliki MX record (bisa menerima email)
        if (!checkdnsrr($email_domain, 'MX')) {
            $error = 'Email yang dimasukkan tidak valid atau tidak dapat menerima email!';
        } else {
            // Cek apakah email sudah ada di database
            $check_email = "SELECT email FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $check_email);
            
            if (mysqli_num_rows($result) > 0) {
                $error = 'Email sudah terdaftar!';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO user (nama, email, password, profil, verifikasi) VALUES ('$nama', '$email', '$hashed_password', 'profil-default.png', 0)";
                
                if (mysqli_query($conn, $query)) {
                    $_SESSION['email_verifikasi'] = $email; // Simpan email di session untuk verifikasi
                    header('Location: verifikasi_akun.php');
                    exit;
                } else {
                    $error = 'Terjadi kesalahan, silakan coba lagi!';
                }
            }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
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
            top: 75%;
            transform: translateY(-50%);
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
        <h2 class="login-title">Aplikasi Peminjaman (Beta)</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"> <?php echo $error; ?> </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama <span style="color: red;">*</span></label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3 password-container">
                <label for="password" class="form-label">Password <span style="color: red;">*</span></label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>
            <div class="mb-3 password-container">
                <label for="konfirmasi_password" class="form-label">Konfirmasi Password <span style="color: red;">*</span></label>
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" placeholder="Ulangi password" required>
                <span class="toggle-password" onclick="togglePassword('konfirmasi_password')">👁️</span>
            </div>
            <button type="submit" class="btn btn-login w-100">Register</button>
        </form>
        <div class="text-center mt-3">
            <small>Sudah punya akun? <a href="login.php">Login di sini</a></small>
        </div>
    </div>
    <script>
        function togglePassword(id) {
            let input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
