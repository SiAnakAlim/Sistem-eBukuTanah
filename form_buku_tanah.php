<?php
include 'koneksi.php';

// Ambil daftar kecamatan dari tabel kecamatan
$sql = "SELECT * FROM kecamatan";
$result = mysqli_query($conn, $sql);

$uploadMessage = "";
$uploadSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $kecamatan = $_POST['kecamatan']; 
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Periksa apakah file sudah dipilih
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $target_dir = "uploads/";
        $file_path = $target_dir . basename($_FILES["file"]["name"]);
        $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $file_size = $_FILES["file"]["size"];

        // Validasi format dan ukuran file
        if ($file_type !== "pdf") {
            $uploadMessage = "Hanya file PDF yang diperbolehkan ❌";
            $uploadSuccess = false;
        } elseif ($file_size > 5 * 1024 * 1024) { // 5MB
            $uploadMessage = "Ukuran file terlalu besar (maksimal 5MB) ❌";
            $uploadSuccess = false;
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_path)) {
                // Jika berhasil mengupload file, simpan data ke database
                $sql = "INSERT INTO buku_tanah (nama, kecamatan, deskripsi, file_path) 
                        VALUES ('$nama', '$kecamatan', '$deskripsi', '$file_path')";
                if (mysqli_query($conn, $sql)) {
                    $uploadMessage = "File berhasil diunggah ✔";
                    $uploadSuccess = true;
                } else {
                    $uploadMessage = "Gagal menyimpan data ke database ❌";
                    $uploadSuccess = false;
                }
            } else {
                $uploadMessage = "Gagal mengunggah file ❌";
                $uploadSuccess = false;
            }
        }
    } else {
        $uploadMessage = "Silakan pilih file PDF untuk diunggah ❌";
        $uploadSuccess = false;
    }
}
?>
<?php
require 'auth.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku Tanah</title>

    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 40px; }
        .container { width: 60%; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        h2 { text-align: center; margin-bottom: 20px; }
        label { font-weight: bold; display: block; margin: 12px 0 6px; }
        input, textarea, select { width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; }
        select { width: calc(100% - 4px); }
        .button-group { display: flex; justify-content: space-between; margin-top: 20px; }
        .btn-save { background: #e6b800; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
        .btn-save:hover { background: #c69500; }
        .btn-back { background: #6c757d; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; text-align: center; }
        .btn-back:hover { background: #545b62; }
        .upload-message { text-align: center; font-size: 16px; margin-top: 10px; padding: 10px; border-radius: 6px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .file-info { font-size: 14px; color: #6c757d; margin-top: -8px; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Buku Tanah</h2>
        <?php if ($uploadMessage): ?>
            <div class="upload-message <?= $uploadSuccess ? 'success' : 'error' ?>">
                <?= $uploadMessage; ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama Buku Tanah</label>
            <input type="text" name="nama" required>

            <label>Kecamatan</label>
            <select name="kecamatan" required>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <option value="<?= $row['nama_kecamatan']; ?>"><?= $row['nama_kecamatan']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Deskripsi (Opsional)</label>
            <textarea name="deskripsi" rows="4"></textarea>

            <label>Unggah File PDF</label>
            <input type="file" name="file" accept=".pdf" required>
            <p class="file-info">Format file: PDF | Maksimal ukuran: 5MB</p>

            <div class="button-group">
                <button type="submit" class="btn-save">Simpan Data</button>
                <a href="index.php" class="btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>
