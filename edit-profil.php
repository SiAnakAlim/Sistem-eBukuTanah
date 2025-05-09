<?php
session_start();
require 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil data user dari database
$query = "SELECT id, nama, email, profil, role FROM user WHERE nama = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$user_id = $row['id'];
$email = $row['email'];
$role = ucfirst($row['role']); // Agar huruf pertama kapital
$current_profil = "assets/img/profil-default.png"; // Default profile
if (!empty($row['profil']) && file_exists("uploads/" . $row['profil'])) {
    $current_profil = "uploads/" . $row['profil'];
}

$upload_error = ''; // Variabel untuk menyimpan pesan error jika ada

// Proses unggah foto profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profil"])) {
    // Jika tidak ada file yang diunggah
    if ($_FILES["profil"]["error"] == UPLOAD_ERR_NO_FILE) {
        $upload_error = "Silakan pilih file untuk diunggah.";
    } else {
        $target_dir = "uploads/";
        $file_extension = strtolower(pathinfo($_FILES["profil"]["name"], PATHINFO_EXTENSION));
        $file_name = $user_id . "." . $file_extension; // Simpan sesuai ID user
        $target_file = $target_dir . $file_name;

        $uploadOk = 1;

        // Validasi apakah file adalah gambar
        $check = getimagesize($_FILES["profil"]["tmp_name"]);
        if ($check === false) {
            $upload_error = "File bukan gambar.";
            $uploadOk = 0;
        }

        // Validasi ukuran file (maks 2MB)
        if ($_FILES["profil"]["size"] > 2000000) {
            $upload_error = "Ukuran file terlalu besar. Maksimal 2MB.";
            $uploadOk = 0;
        }

        // Validasi format file
        if (!in_array($file_extension, ["jpg", "jpeg", "png"])) {
            $upload_error = "Hanya format JPG, JPEG, dan PNG yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Simpan file jika valid
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["profil"]["tmp_name"], $target_file)) {
                // Update database dengan nama file baru
                $query = "UPDATE user SET profil = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $file_name, $user_id);
                if ($stmt->execute()) {
                    // Perbarui session agar langsung terlihat di navbar
                    $_SESSION['profil'] = $file_name;
                    header("Location: edit-profil.php");
                    exit;
                } else {
                    $upload_error = "Gagal menyimpan ke database.";
                }
            } else {
                $upload_error = "Gagal mengunggah file.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
            margin-bottom: 15px;
        }
        .btn-save {
            background-color: #ffcc00;
            color: #333;
            font-weight: bold;
            border-radius: 25px;
        }
        .btn-save:hover {
            background-color: #e6b800;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            font-weight: bold;
            border-radius: 25px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .info-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            text-align: left;
            margin-bottom: 15px;
            border-left: 5px solid #ffcc00;
        }
        .info-box p {
            margin: 5px 0;
            font-weight: bold;
        }
        .info-box span {
            font-weight: normal;
            color: #333;
        }
        .error-message {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-message {
            font-weight: bold;
            color: #333;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-3">Edit Profil</h2>
        <img src="<?php echo $current_profil; ?>?t=<?php echo time(); ?>" alt="Profil" class="profile-img">
        
        <div class="info-box">
    <p>👤 Username: <span><?php echo htmlspecialchars($username); ?></span></p>
    <p>📧 Email: <span><?php echo htmlspecialchars($email); ?></span></p>
    <p>🔰 Role: <span><?php echo htmlspecialchars($role); ?></span></p>
</div>


        <?php if ($upload_error): ?>
            <div class="error-message"><?php echo $upload_error; ?></div>
        <?php endif; ?>

        <form action="edit-profil.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="profil" class="form-label">Ganti Foto Profil</label>
                <input type="file" name="profil" id="profil" class="form-control" accept="image/*">
                <small class="info-message">Format yang diterima: JPG, JPEG, PNG. Maksimal ukuran file: 2MB.</small>
            </div>
            <button type="submit" class="btn btn-save w-100 mb-2">Simpan Perubahan</button>
        </form>
        <a href="index.php" class="btn btn-back w-100">Kembali</a>
    </div>
</body>
</html>
