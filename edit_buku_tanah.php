<?php
include 'koneksi.php';

// Cek apakah ada parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = intval($_GET['id']); // Pastikan ID adalah angka
$sql = "SELECT * FROM buku_tanah WHERE id = $id";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data tidak ditemukan.");
}

// Ambil daftar kecamatan
$sqlKecamatan = "SELECT * FROM kecamatan";
$resultKecamatan = mysqli_query($conn, $sqlKecamatan);
?>
<?php
require 'auth.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku Tanah</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 60%;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        .required::after {
            content: " *";
            color: red;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="file"] {
            padding: 5px;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"] {
            background-color: #e6b800;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #d19e00;
        }

        .btn-back {
            background-color: #888;
            color: white;
            margin-top: 10px;
        }

        .btn-back:hover {
            background-color: #666;
        }

        .file-info {
            background-color: #f7f7f7;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #e1e1e1;
            margin-top: 10px;
        }

        .file-info a {
            color: #007bff;
            text-decoration: none;
        }

        .file-info a:hover {
            text-decoration: underline;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Buku Tanah</h2>
        <form action="update_buku_tanah.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            
            <label for="nama" class="required">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?= $data['nama'] ?>" required>
            
            <label for="kecamatan" class="required">Kecamatan:</label>
            <select id="kecamatan" name="kecamatan" required>
                <?php while ($row = mysqli_fetch_assoc($resultKecamatan)): ?>
                    <option value="<?= $row['id']; ?>" <?= $data['kecamatan'] == $row['id'] ? 'selected' : '' ?>>
                        <?= $row['nama_kecamatan']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi"><?= $data['deskripsi'] ?></textarea>

            <label for="file_buku_tanah">File Buku Tanah (PDF):</label>
            <input type="file" id="file_buku_tanah" name="file_buku_tanah" accept=".pdf">
            
            <?php if (!empty($data['file_path'])): ?>
                <div class="file-info">
                    <p>File saat ini: <a href="<?= $data['file_path'] ?>" target="_blank">Lihat PDF</a></p>
                </div>
            <?php endif; ?>

            <button type="submit">Update</button>
            <button type="button" class="btn-back" onclick="window.location.href='inventaris_buku.php'">Kembali</button>
        </form>
    </div>
</body>
</html>
