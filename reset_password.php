<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die("Token tidak ditemukan!");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e3e3e3, #ffffff);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 380px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            padding: 25px;
            text-align: center;
        }
        h2 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }
        p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }
        .form-group {
            text-align: left;
            margin-bottom: 15px;
            position: relative;
        }
        label {
            font-size: 14px;
            font-weight: 500;
            color: #444;
        }
        .form-control {
            width: 100%;
            padding: 10px 5px 10px 10px; 
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #ffcc00;
            box-shadow: 0 0 5px rgba(255, 204, 0, 0.5);
            outline: none;
        }
        .btn {
            background-color: #ffcc00;
            color: #333;
            border: none;
            font-weight: bold;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background-color: #e6b800;
            transform: translateY(-2px);
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .countdown {
            font-size: 16px;
            font-weight: bold;
            color: #d9534f;
            margin-top: 5px;
        }
        .links {
            margin-top: 15px;
            font-size: 14px;
        }
        .links a {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .links a:hover {
            color: #0056b3;
        }
        /* Icon mata */
        .eye-icon {
            position: absolute;
            right: -1px;
            top: 70%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <p>Silakan masukkan password baru Anda.</p>

        <div class="alert">
            <p>Token akan kadaluarsa dalam:</p>
            <p class="countdown" id="countdown"></p>
        </div>

        <form method="POST" action="proses_reset.php" onsubmit="return validatePassword()">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            
            <div class="form-group">
                <label for="password">Password Baru:</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <i class="fa-solid fa-eye-slash eye-icon" onclick="togglePassword('password', this)"></i>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                <i class="fa fa-eye-slash eye-icon" onclick="togglePassword('confirm_password', this)"></i>
            </div>

            <p id="error_message" style="color: red; font-size: 14px;"></p>

            <button type="submit" name="reset_password" class="btn">Ubah Password</button>
        </form>

        <div class="links">
            <a href="forgot_password.php">Kembali ke Lupa Password</a> || <a href="login.php">Kembali ke Login</a>
        </div>
    </div>

    <script>
        // Countdown Timer with LocalStorage
        function startCountdown() {
            let countdownElement = document.getElementById('countdown');
            if (!countdownElement) return;

            let storedTime = localStorage.getItem("countdown_time");
            let now = Math.floor(Date.now() / 1000);
            
            if (storedTime && now < storedTime) {
                timeRemaining = storedTime - now;
            } else {
                timeRemaining = 600; // 10 menit
                localStorage.setItem("countdown_time", now + timeRemaining);
            }

            let interval = setInterval(() => {
                let minutes = Math.floor(timeRemaining / 60);
                let seconds = timeRemaining % 60;
                countdownElement.innerText = minutes + " menit " + seconds + " detik";

                if (timeRemaining <= 0) {
                    clearInterval(interval);
                    localStorage.removeItem("countdown_time");
                    alert("Token telah kadaluarsa! Silakan request ulang.");
                    window.location.href = "forgot_password.php";
                }

                timeRemaining--;
            }, 1000);
        }
        startCountdown();

        // Password Validation
        function validatePassword() {
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;
            let errorMessage = document.getElementById("error_message");

            let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,20}$/;

            if (!regex.test(password)) {
                errorMessage.innerText = "Password harus 8-20 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter spesial.";
                return false;
            }
            
            if (password !== confirmPassword) {
                errorMessage.innerText = "Konfirmasi password tidak cocok!";
                return false;
            }

            return true;
        }

        function togglePassword(inputId, icon) {
            let input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>
</html>
