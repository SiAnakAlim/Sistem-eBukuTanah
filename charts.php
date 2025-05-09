<?php
require 'koneksi.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$filter = isset($_POST['filter']) ? $_POST['filter'] : 'harian';
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Validasi tanggal
if ($startDate && $endDate) {
    $startDate = date('Y-m-d', strtotime($startDate));
    $endDate = date('Y-m-d', strtotime($endDate));
    if ($startDate > $endDate) {
        $error = "Tanggal mulai harus sebelum tanggal selesai.";
    }
}

$query = "SELECT DATE(tanggal_pinjam) AS tanggal, COUNT(*) AS jumlah FROM peminjaman";
if ($startDate && $endDate && !isset($error)) {
    $query .= " WHERE tanggal_pinjam BETWEEN '$startDate' AND '$endDate'";
}
$query .= " GROUP BY DATE(tanggal_pinjam) ORDER BY tanggal ASC";
$result = $conn->query($query);

$chartData = ['labels' => [], 'data' => []];
$totalPeminjaman = 0;
$maxHarian = ["tanggal" => "", "jumlah" => 0];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chartData['labels'][] = $row['tanggal'];
        $chartData['data'][] = $row['jumlah'];
        $totalPeminjaman += $row['jumlah'];
        if ($row['jumlah'] > $maxHarian['jumlah']) {
            $maxHarian = $row;
        }
    }
}

$queryBulan = "SELECT CONCAT(YEAR(tanggal_pinjam), '-', LPAD(MONTH(tanggal_pinjam), 2, '0')) AS bulan, COUNT(*) AS jumlah FROM peminjaman";
if ($startDate && $endDate && !isset($error)) { // Gunakan validasi yang sama
    $queryBulan .= " WHERE tanggal_pinjam BETWEEN '$startDate' AND '$endDate'";
}
$queryBulan .= " GROUP BY YEAR(tanggal_pinjam), MONTH(tanggal_pinjam) ORDER BY bulan ASC";
$resultBulan = $conn->query($queryBulan);

$chartBulanData = ['labels' => [], 'data' => []];
$maxBulanan = ["bulan" => "", "jumlah" => 0];
if ($resultBulan->num_rows > 0) {
    while ($row = $resultBulan->fetch_assoc()) {
        $chartBulanData['labels'][] = $row['bulan'];
        $chartBulanData['data'][] = $row['jumlah'];
        if ($row['jumlah'] > $maxBulanan['jumlah']) {
            $maxBulanan = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjaman</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .chart-container, .stats-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Spasi antar chart */
        }
        .header-title {
            text-align: center;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .stats-container .stat-box {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        .total {
            background-color: #007bff;
        }
        .max-harian {
            background-color: #28a745;
        }
        .max-bulanan {
            background-color: #ffc107;
        }
        .date-range-form {
            margin-bottom: 20px;
        }
        .date-range-form label {
            margin-right: 10px;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .chart-row { /* Kelas untuk mengatur layout chart */
            display: flex;
            flex-wrap: wrap; /* Agar chart bisa wrap ke baris berikutnya */
            justify-content: space-between; /* Untuk memberi jarak antar chart */
        }
        .chart-col { /* Kelas untuk mengatur lebar chart */
            flex: 0 0 48%; /* Setiap chart mengambil hampir setengah lebar */
        }
        .filter-form {
            display: flex;
            justify-content: center; /* Tengahkan form */
            margin-bottom: 20px;
        }
        .filter-form > * { /* Style langsung anak dari filter-form */
            margin: 0 5px; /* Spasi antar elemen form */
        }
        .clear-filter {
            margin-left: 10px; /* Spasi antara tombol filter dan clear */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="header-title">Statistik Peminjaman Buku Tanah</h2>

        <form method="post" class="filter-form">  <label for="start_date">Tanggal Mulai:</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo $startDate; ?>">
            <label for="end_date">Tanggal Selesai:</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo $endDate; ?>">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <button type="button" class="btn btn-secondary clear-filter" onclick="clearFilter()">Clear</button>
        </form>

        <div class="chart-row">
            <div class="chart-col">
                <div class="chart-container">
                    <h5 class="text-center">Peminjaman Harian</h5>
                    <canvas id="chartPeminjaman"></canvas>
                </div>
            </div>
            <div class="chart-col">
                <div class="chart-container">
                    <h5 class="text-center">Peminjaman per Bulan</h5>
                    <canvas id="chartBulan"></canvas>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4 stats-container">
            <div class="col-md-4">
                <div class="stat-box total">Total Peminjaman<br><?php echo $totalPeminjaman; ?> kali</div>
            </div>
            <div class="col-md-4">
                <div class="stat-box max-harian">Peminjaman Harian Tertinggi<br><?php echo $maxHarian['jumlah']; ?> kali pada <?php echo $maxHarian['tanggal']; ?></div>
            </div>
            <div class="col-md-4">
                <div class="stat-box max-bulanan">Peminjaman Bulanan Tertinggi<br><?php echo $maxBulanan['jumlah']; ?> kali pada <?php echo $maxBulanan['bulan']; ?></div>
            </div>
        </div>
        <div class="mt-3 text-center">
            <a href="index.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script>
        const chartData = <?php echo json_encode($chartData); ?>;
        new Chart(document.getElementById('chartPeminjaman').getContext('2d'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: chartData.data,
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            }
        });
        const chartBulanData = <?php echo json_encode($chartBulanData); ?>;
        new Chart(document.getElementById('chartBulan').getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartBulanData.labels,
                datasets: [{
                    label: 'Jumlah Peminjaman per Bulan',
                    data: chartBulanData.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        function clearFilter() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            // Submit form untuk mereset filter (opsional, bisa juga reload halaman)
            // document.forms[0].submit();  // Ambil form pertama dan submit
            window.location.href = window.location.pathname; // Reload halaman
        }
    </script>
</body>
</html>