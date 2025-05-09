<?php
session_start();
require 'koneksi.php';

// Cek apakah OTP sudah diverifikasi
if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['reset_email'])) {
    header('Location: reset_password.php');
    exit;
}

$email = $_SESSION['reset_email'];
$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);

// Proses update password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Password dan konfirmasi tidak cocok!";
        header('Location: create_new_password.php');
        exit;
    }
    
    // Hash password baru
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Update password di database
    mysqli_query($conn, "UPDATE user SET password='$hashed_password' WHERE email='$email'");
    
    // Hapus session terkait reset password
    unset($_SESSION['otp_verified'], $_SESSION['reset_email']);
    
    $_SESSION['success'] = "Password berhasil diubah! Silakan login.";
    header('Location: login.php');
    exit;
}
?>

<body>
    <div class="password-container">
        <h2 class="password-title">Buat Password Baru</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi password baru" required>
            </div>
            <button type="submit" class="btn btn-reset w-100">Simpan Password</button>
            <div class="text-center mt-3">
                <a href="login.php">Kembali ke Login</a>
            </div>
        </form>
    </div>
</body>
