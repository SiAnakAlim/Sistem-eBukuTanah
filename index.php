<?php
session_start();

// Jika user belum login, arahkan ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Jika sudah login, lanjutkan dengan koneksi database dan konten lainnya
require 'koneksi.php';


$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Query gabungan untuk mengambil profil dan jumlah peminjaman sekaligus
$query = "
    SELECT 
        u.profil, 
        SUM(CASE WHEN p.status = 'Dipinjam' THEN 1 ELSE 0 END) AS totalDipinjam,
        SUM(CASE WHEN p.status = 'Dikembalikan' THEN 1 ELSE 0 END) AS totalDikembalikan
    FROM user u
    LEFT JOIN peminjaman p ON 1=1
    WHERE u.nama = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$current_profil = (!empty($row['profil'])) ? "uploads/" . htmlspecialchars($row['profil']) : "assets/img/profil-default.png";
$totalDipinjam = $row['totalDipinjam'] ?? 0;
$totalDikembalikan = $row['totalDikembalikan'] ?? 0;

// Query untuk chart per hari
$queryChart = "
    SELECT DATE(tanggal_pinjam) AS tanggal, COUNT(*) AS jumlah 
    FROM peminjaman 
    WHERE tanggal_pinjam >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
    GROUP BY DATE(tanggal_pinjam) 
    ORDER BY tanggal ASC
";
$resultChart = $conn->query($queryChart);
$chartData = ['labels' => [], 'data' => []];
while ($row = $resultChart->fetch_assoc()) {
    $chartData['labels'][] = $row['tanggal'];
    $chartData['data'][] = $row['jumlah'];
}

// Query untuk chart per bulan
$queryChartBulan = "
    SELECT CONCAT(YEAR(tanggal_pinjam), '-', LPAD(MONTH(tanggal_pinjam), 2, '0')) AS bulan, COUNT(*) AS jumlah 
    FROM peminjaman 
    WHERE tanggal_pinjam >= DATE_SUB(NOW(), INTERVAL 1 YEAR) 
    GROUP BY YEAR(tanggal_pinjam), MONTH(tanggal_pinjam) 
    ORDER BY bulan ASC
";
$resultChartBulan = $conn->query($queryChartBulan);
$chartBulanData = ['labels' => [], 'data' => []];
while ($row = $resultChartBulan->fetch_assoc()) {
    $chartBulanData['labels'][] = $row['bulan'];
    $chartBulanData['data'][] = $row['jumlah'];
}
?>






<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard</title>
        <link href="styles.css" rel="stylesheet" />
        <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />

        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">
            <div class="logo-container" style="text-align: center;">
                <div>Aplikasi Peminjaman<img src="assets/img/logo-bpn.png" alt="Logo BPN" style="height: 25px; margin-left:4px; "></div>
            </div>
        </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <!-- <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div> -->
        </form>
        <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $current_profil; ?>" alt="Profil" style="height: 30px; width: 30px; border-radius: 50%; object-fit: cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="edit-profil.php">Settings</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>

    </nav>

        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
    <div class="nav">
        <div class="sb-sidenav-menu-heading">Core</div>
        <a class="nav-link" href="index.php">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
        </a>
        
        <div class="sb-sidenav-menu-heading">Peminjaman</div>
        
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
            Buku Tanah
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav">
        <a class="nav-link" href="form-bt.php">Peminjaman BT</a>
        <?php if (in_array($role, ['Pimpinan Kantor', 'Admin', 'Bagian BT'])) : ?>
            <a class="nav-link" href="tabel_bt.php">Tabel BT</a>
        <?php endif; ?>
    </nav>
</div>
         <!-- Surat Ukur -->
<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSuratUkur" aria-expanded="false" aria-controls="collapseSuratUkur">
    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
    Surat Ukur
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<!-- Surat Ukur -->
<div class="collapse" id="collapseSuratUkur" aria-labelledby="headingSuratUkur" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav">
        <a class="nav-link" href="form_su.php">Peminjaman SU</a>
        <?php if (in_array($role, ['Pimpinan Kantor', 'Admin', 'Bagian SU'])) : ?>
            <a class="nav-link" href="tabel_su.php">Tabel SU</a>
        <?php endif; ?>
    </nav>
</div>

<!-- Warkah -->
<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseWarkah" aria-expanded="false" aria-controls="collapseWarkah">
    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
    Warkah
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<div class="collapse" id="collapseWarkah" aria-labelledby="headingWarkah" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav">
        <a class="nav-link" href="form_warkah.php">Peminjaman Warkah</a>
        <?php if (in_array($role, ['Pimpinan Kantor', 'Admin', 'Bagian Warkah'])) : ?>
            <a class="nav-link" href="tabel_warkah.php">Tabel Warkah</a>
        <?php endif; ?>
    </nav>
</div>


        
        <div class="sb-sidenav-menu-heading">Statistik</div>
        <!-- <a class="nav-link" href="charts.php">
            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
            Charts
        </a> -->
        <a class="nav-link" 
   href="<?php echo in_array($role, ['Pimpinan Kantor', 'Admin', ]) ? 'pengaturan_akun.php' : '#'; ?>" 
   style="<?php echo in_array($role, ['Pimpinan Kantor', 'Admin', ]) ? '' : 'pointer-events: none; opacity: 0.5; cursor: not-allowed;'; ?>">
    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
    Manajemen Akun
</a>




    </div>
</div>

                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </div>

                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                        <div class="col-12">
    <div class="card bg-light text-dark shadow-sm p-4">
    <h3 class="mb-2">Selamat Datang, <?php echo $username; ?>!</h3>

        <p class="mb-0">Kelola data peminjaman dan pengembalian dengan mudah dan efisien.</p>
    </div>
</div>

                        <!-- <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Jumlah Dikembalikan: <?php echo $totalDikembalikan; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="layout-sidenav-light.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Jumlah Dipinjam: <?php echo $totalDipinjam; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="layout-sidenav-light.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Success Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Danger Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div> -->
                        </div> 
                        <!-- <div class="row">
                            <div class="col-xl-6 col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area"></i>
                                        Chart Peminjaman Harian
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chartPeminjaman"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar"></i>
                                        Chart Peminjaman Bulanan
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chartBulan"></canvas>
                                    </div>
                                </div>
                            </div>
                         </div> -->


                        <!-- <div class="card mb-4">
                        <div class="card-header">
    <i class="fas fa-table me-1"></i>
    <a href="inventaris_buku.php" style="text-decoration: none; color: inherit;">
        Data Inventaris Buku Tanah
    </a>
</div>

    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Nama Buku Tanah</th>
                    <th>Kelurahan</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Nama Buku Tanah</th>
                    <th>Kelurahan</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                    // Ambil data inventaris dari database
                    $query = "SELECT * FROM buku_tanah";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . (isset($row['kecamatan']) ? htmlspecialchars($row['kecamatan']) : 'Data kecamatan tidak tersedia') . "</td>";
                            echo "<td>" . htmlspecialchars($row['deskripsi']) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($row['file_path']) . "' target='_blank'>Lihat File</a></td>";
                            echo "<td><a href='edit_buku_tanah.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No records found.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

                    </div>
                </main> -->
                <footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Aryamukti Satria Hendrayana 2025</div>
            <div>
                <a href="privasi.php">Privacy Policy</a>
                &middot;
                <a href="petunjuk.php">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>
            </div>
        </div>
        <script>
        $(document).ready(function() {
    $.ajax({
        url: 'charts.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error("Error from charts.php:", response.error);
                return; // Hentikan jika ada kesalahan dari PHP
            }

            const chartData = response.chartData;
            const chartBulanData = response.chartBulanData;

            // Grafik Peminjaman Harian
            const ctxLine = $('#chartPeminjaman')[0].getContext('2d'); // Gunakan jQuery
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: chartData.data,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
            });

            // Grafik Peminjaman Bulanan
            const ctxBar = $('#chartBulan')[0].getContext('2d'); // Gunakan jQuery
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: chartBulanData.labels,
                    datasets: [{
                        label: 'Jumlah Peminjaman per Bulan',
                        data: chartBulanData.data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                }
            });
        },
        error: function(error) {
            console.error("Error fetching chart data:", error);
        }
    });
});
    </script>
        <script>
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple, {
            perPage: 10, // Menampilkan 10 entri per halaman
            search: true, // Aktifkan pencarian
            paging: true, // Aktifkan pagination
            showing: true // Menampilkan informasi "Showing X to Y of Z entries"
        });
    }
</script>
<script>
// Data Chart Peminjaman
const chartData = <?php echo json_encode($chartData); ?>;
const ctxLine = document.getElementById('chartPeminjaman').getContext('2d');

// Line Chart for Peminjaman
const chartLine = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: chartData.labels,
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: chartData.data,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    }
});

// Data Chart Bar Peminjaman per Bulan
const chartBulanData = <?php echo json_encode($chartBulanData); ?>;
const ctxBar = document.getElementById('chartBulan').getContext('2d');

// Bar Chart for Peminjaman per Bulan
const chartBar = new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: chartBulanData.labels,
        datasets: [{
            label: 'Jumlah Peminjaman per Bulan',
            data: chartBulanData.data,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    }
});
</script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>