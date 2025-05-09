<?php
include 'koneksi.php'; // Pastikan koneksi ke database sudah dimuat

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data peminjaman berdasarkan ID
$query = "SELECT * FROM peminjaman WHERE id = $id";
$result = $conn->query($query);

// Periksa apakah data ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Data tidak ditemukan.");
}

// Proses pembaruan data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal_hari = $_POST['tanggal_hari'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'] ? "'".$_POST['tanggal_kembali']."'" : "NULL";  // Menangani NULL dengan benar
    $jenis_hak = $_POST['jenis_hak'];
    $keterangan = $_POST['keterangan'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $status = $_POST['status'];

    // Proses file keterangan
    $file_keterangan = $row['file_keterangan'];
    if (!empty($_FILES["file_keterangan"]["name"])) {
        $target_dir = "uploads/";
        $file_keterangan = time() . "_" . basename($_FILES["file_keterangan"]["name"]);
        $target_file = $target_dir . $file_keterangan;
        move_uploaded_file($_FILES["file_keterangan"]["tmp_name"], $target_file);
    }

    // Proses tanda tangan (Base64 ke file)
    $tanda_tangan = $row['tanda_tangan'];
    if (!empty($_POST['tanda_tangan'])) {
        $img = $_POST['tanda_tangan'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $tanda_tangan = "uploads/ttd_" . time() . ".png";
        file_put_contents($tanda_tangan, $data);
    }

    // Query update data
    $update_query = "UPDATE peminjaman SET 
        tanggal_hari = '$tanggal_hari', 
        tanggal_pinjam = '$tanggal_pinjam',
        tanggal_kembali = $tanggal_kembali,
        jenis_hak = '$jenis_hak',
        keterangan = '$keterangan',
        file_keterangan = '$file_keterangan',
        nama_peminjam = '$nama_peminjam',
        status = '$status',
        tanda_tangan = '$tanda_tangan'
        WHERE id = $id";

    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='tabel_bt.php';</script>";
    } else {
        echo "Error: " . $conn->error;
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
    <title>Edit Data Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
            color: #333;
        }
        .btn-submit {
            background-color: #e6b800;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 12px 20px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #d89e00;
        }
        .form-control, .form-select, textarea {
            border-radius: 8px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            padding: 12px;
        }
        .form-control:focus, .form-select:focus, textarea:focus {
            border-color: #e6b800;
            box-shadow: 0 0 0 0.25rem rgba(230, 184, 0, 0.25);
        }
        .file-link {
            color: #007bff;
            text-decoration: none;
        }
        .file-link:hover {
            text-decoration: underline;
        }
        .small {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1>Edit Data Peminjaman</h1>
    <a href="tabel_bt.php" class="btn btn-secondary mb-3">Kembali</a>

    <form action="update.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
            <input type="text" class="form-control" name="nama_peminjam" value="<?php echo $row['nama_peminjam']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
            <input type="date" class="form-control" name="tanggal_pinjam" value="<?php echo $row['tanggal_pinjam']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="jenis_hak" class="form-label">Jenis Hak</label>
            <input type="text" class="form-control" name="jenis_hak" value="<?php echo $row['jenis_hak']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status">
                <option value="Dipinjam" <?php echo $row['status'] == 'Dipinjam' ? 'selected' : ''; ?>>Dipinjam</option>
                <option value="Dikembalikan" <?php echo $row['status'] == 'Dikembalikan' ? 'selected' : ''; ?>>Dikembalikan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan"><?php echo $row['keterangan']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="file_keterangan" class="form-label">File Keterangan</label>
            <input type="file" class="form-control" name="file_keterangan">
            <?php if (!empty($row['file_keterangan'])): ?>
                <small>File sebelumnya: <a class="file-link" href="uploads/<?php echo $row['file_keterangan']; ?>" target="_blank">Lihat File</a></small>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="tanda_tangan" class="form-label">Tanda Tangan</label>
            <input type="file" class="form-control" name="tanda_tangan">
            <?php if (!empty($row['tanda_tangan'])): ?>
                <small>File tanda tangan sebelumnya: <a class="file-link" href="uploads/<?php echo $row['tanda_tangan']; ?>" target="_blank">Lihat File</a></small>
            <?php endif; ?>
        </div>
        <!-- Form fields here -->
    <button type="submit" class="btn btn-submit">Simpan</button>
    <!-- Menambahkan hidden field untuk ID -->
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
