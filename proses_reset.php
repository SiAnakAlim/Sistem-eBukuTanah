<?php
session_start();
require 'koneksi.php'; // Pastikan file koneksi sudah benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reset_password']) && isset($_POST['token']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
        $token = $_POST['token'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi password
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Konfirmasi password tidak cocok!";
            header("Location: reset_password.php?token=" . urlencode($token));
            exit();
        }

        // Validasi keamanan password
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,20}$/', $password)) {
            $_SESSION['error'] = "Password harus 8-20 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter spesial.";
            header("Location: reset_password.php?token=" . urlencode($token));
            exit();
        }

        // Hash password baru
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah token valid
        $query = $conn->prepare("SELECT email FROM user WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $query->bind_param("s", $token);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $email = $row['email'];

            // Update password di database
            $update = $conn->prepare("UPDATE user SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE email = ?");
            $update->bind_param("ss", $hashed_password, $email);

            if ($update->execute()) {
                $_SESSION['success'] = "Password berhasil diperbarui! Silakan login.";
                header("Location: reset_success.php");
                exit(); // Pastikan script berhenti di sini
            } else {
                $_SESSION['error'] = "Terjadi kesalahan saat memperbarui password!";
            }
        } else {
            $_SESSION['error'] = "Token tidak valid atau telah kadaluarsa!";
        }
    }
}

header("Location: reset_password.php?token=" . urlencode($_POST['token']));
exit();
?>
