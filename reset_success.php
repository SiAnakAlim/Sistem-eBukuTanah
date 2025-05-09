<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Diubah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f7f7f7, #ffffff);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            width: 360px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            opacity: 0;
            transform: translateY(-10px);
            animation: fadeIn 0.6s ease-in-out forwards;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .icon-success {
            font-size: 50px;
            color: #4CAF50;
            margin-bottom: 15px;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 12px;
            color: #333;
            font-weight: 600;
        }
        p {
            font-size: 15px;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .btn {
            background-color: #ffcc00;
            color: #333;
            border: none;
            font-weight: bold;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: auto;
            min-width: 150px;
        }
        .btn:hover {
            background-color: #e6b800;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <i class="fa-solid fa-circle-check icon-success"></i>
        <h2>Password Berhasil Diubah</h2>
        <p>Password kamu telah berhasil diperbarui. Silakan login dengan password baru.</p>
        
        <a href="login.php" class="btn">Ke Halaman Login</a>
    </div>
</body>
</html>
