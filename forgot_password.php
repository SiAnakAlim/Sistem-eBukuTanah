<?php
require 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if (isset($_POST['reset_request'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert error'>
                        <p><b>Oops!</b> Format email tidak valid.</p>
                    </div>";
    } else {
        // Cek apakah email ada di database
        $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
        
        if (mysqli_num_rows($query) > 0) {
            // Buat token unik & waktu kadaluarsa
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', time() + 1800); // Token berlaku 30 menit

            // Simpan token ke database
            mysqli_query($conn, "UPDATE user SET reset_token = '$token', reset_token_expiry = '$expiry' WHERE email = '$email'");

            // Buat link reset password
            $resetLink = "http://localhost/ProjectKerjaPraktek/reset_password.php?token=$token";
            // $resetLink = "https://peminjamankantahkotayk.com/reset_password.php?token=$token";


            // Simpan atau kirim email ke pengguna
            // mail($email, "Reset Password", "Klik link berikut untuk reset password: $resetLink");

            // Pesan sukses
            $message = "<div class='alert success'>
    <p><b>Berhasil!</b> Gunakan link berikut untuk reset password:</p>
    <p style='margin-top: 15px; text-align: center;'>
        <a href='$resetLink' style='
            display: inline-block; 
            padding: 10px 15px; 
            background-color: #28a745; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: bold;
            border: 2px solid #218838;'>
            🔒 Klik di sini untuk reset password
        </a>
    </p>
</div>";

        } else {
            // Pesan error jika email tidak ditemukan
            $message = "<div class='alert error'>
                            <p><b>Oops!</b> Email tidak ditemukan di sistem kami.</p>
                        </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <style>
        body {
            background: linear-gradient(135deg, #d1d1d1, #f4f4f9);
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 450px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            padding: 30px;
            text-align: center;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .btn {
            background-color: #ffcc00;
            color: #333;
            border: none;
            font-weight: bold;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }
        .btn:hover {
            background-color: #e6b800;
            transform: scale(1.05);
        }
        .back-link {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
        .success {
            background-color: #d4edda; /* Hijau pucat */
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da; /* Merah muda lembut */
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lupa Password</h2>
        <p>Masukkan email Anda untuk mendapatkan link reset password.</p>
        
        <?php if (isset($message)) echo $message; ?>

        <form method="POST">
            <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
            <button type="submit" name="reset_request" class="btn">Reset Password</button>
        </form>

        <a href="login.php" class="back-link">Kembali ke Login</a>
    </div>
</body>
</html>
