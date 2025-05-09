<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login atau register
if (!isset($_SESSION['email_verifikasi'])) {
    header('Location: register.php');
    exit;
}

$email = $_SESSION['email_verifikasi'];
$query = "SELECT verifikasi FROM user WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$status_verifikasi = $user['verifikasi'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .container {
            width: 80%;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            color: white;
            background-color: red;
            transition: background-color 0.5s ease-in-out;
        }
        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-refresh {
            background-color: white;
            color: black;
            border: 1px solid black;
        }
        .btn-continue {
            background-color: white;
            color: green;
            border: 2px solid green;
            font-weight: bold;
        }
        .notif {
            display: none;
            margin-top: 15px;
            color: yellow;
            font-weight: bold;
        }
    </style>

    <script>
        function cekVerifikasi() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "cek_status_verifikasi_akun.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (xhr.responseText == "1") {
                        document.querySelector(".container").style.backgroundColor = "green";
                        document.getElementById("status-text").innerText = "Akun Anda Sudah Diverifikasi!";
                        document.getElementById("desc-text").innerText = "Silakan masuk ke halaman login untuk melanjutkan.";
                        var btn = document.getElementById("refresh-btn");
                        btn.innerText = "Lanjutkan";
                        btn.classList.remove("btn-refresh");
                        btn.classList.add("btn-continue");
                        btn.onclick = function () {
                            window.location.href = 'login.php';
                        };
                        document.getElementById("notif").style.display = "none"; // Hilangkan notif jika berhasil
                    } else {
                        document.getElementById("notif").style.display = "block"; // Tampilkan notif jika belum diverifikasi
                    }
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>

<div class="container">
    <h2 id="status-text">Akun Anda Belum Diverifikasi</h2>
    <p id="desc-text">Silakan tunggu hingga admin memverifikasi akun Anda.</p>
    <p>Klik tombol di bawah untuk mengecek status.</p>
    <button id="refresh-btn" class="btn btn-refresh" onclick="cekVerifikasi()">Refresh</button>
    <p id="notif" class="notif">Verifikasi belum disetujui oleh admin, harap tunggu.</p>
</div>

</body>
</html>
