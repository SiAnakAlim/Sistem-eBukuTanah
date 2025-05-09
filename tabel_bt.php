<?php
session_start(); // Pastikan session dimulai
include 'koneksi.php';

// Ambil role user dari session
$role = $_SESSION['role'] ?? 'umum'; // Default ke 'umum' jika tidak ada session role

// Debugging untuk cek role
// echo "<script>console.log('Role: " . $role . "');</script>";

// Pencarian
$search_query = "";
$search_term = $_GET['search'] ?? '';
$where_clauses = [];

if (!empty($search_term)) {
    $search_term = $conn->real_escape_string($search_term); // Hindari SQL Injection
    $where_clauses[] = "(nama_peminjam LIKE '%$search_term%' OR jenis_hak LIKE '%$search_term%')";
}

// Filter Status
$status_filter = $_GET['status'] ?? 'all';
if ($status_filter == "Dipinjam") {
    $where_clauses[] = "status = 'Dipinjam'";
} elseif ($status_filter == "Dikembalikan") {
    $where_clauses[] = "status = 'Dikembalikan'";
}

// Gabungkan WHERE hanya jika ada filter
$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Pagination
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; // Pastikan page minimal 1
$offset = ($page - 1) * $limit;

// Query utama
$query = "SELECT * FROM peminjaman $where_sql ORDER BY tanggal_pinjam ASC, id ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

// Total data (dengan filter yang sesuai)
$total_query = "SELECT COUNT(*) as total FROM peminjaman $where_sql";
$total_result = $conn->query($total_query);
$total_data = $total_result->fetch_assoc()['total'] ?? 0;
$total_pages = ceil($total_data / $limit);

// Statistik total
$dikembalikan_result = $conn->query("SELECT COUNT(*) as dikembalikan FROM peminjaman WHERE status = 'Dikembalikan'")->fetch_assoc();
$dipinjam_result = $conn->query("SELECT COUNT(*) as dipinjam FROM peminjaman WHERE status = 'Dipinjam'")->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />
    <style>
        body {
            background-color: #f4f6f9;
        }
        .table-container {
    max-width: 87%; /* Atur lebar maksimum tabel */
    margin: 0 auto; /* Agar tetap di tengah */
    padding: 20px; /* Beri sedikit ruang di sekitar */
    background-color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

        .container {
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-sm {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }
        .checkbox-status-kembali {
            width: 20px;
            height: 20px;
        }
        .checked-status {
            background-color: #28a745;
            color: white;
        }
        .pagination .page-item .page-link {
            color: #007bff;
        }
        .pagination .page-item.active .page-link {
            background-color: #e6b800;
            border-color: #e6b800;
        }
        .btn-action {
            width: 100%;
            font-size: 14px;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }
        .table-bordered {
            border: 1px solid #ddd;
        }
        .btn-action:hover {
            background-color: #e6b800;
        }
        .form-inline {
            margin-bottom: 20px;
        }
        .page-link {
            padding: 0.5rem 1rem;
        }
        .status-kembali {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: transparent;
            border: 2px solid #007bff;
        }
        .status-kembali.dikembalikan {
            background-color: green;
        }
        .search-input {
            margin-bottom: 20px;
            width: 100%;
            max-width: 400px;
            margin-left: 0;
        }
        .pagination {
            justify-content: center;
            max-width
        }
        .status-aksi {
            font-weight: bold;
        }
        .status-aksi.dikembalikan {
            color: green;
        }
        .status-aksi.peminjaman {
            color: red;
        }
        .jenis-hak-col {
            width: 200px;
        }
        .summary-box {
            display: inline-block;
            padding: 10px;
            background-color: #e6b800;
            color: white;
            margin-right: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .table td, .table th {
            text-align: center;
        }
        .wide-col {
            width: 200px;
        }
        .narrow-col {
            width: 120px;
        }

        /* Flexbox untuk posisi */
        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Animasi */
        .animated-fade {
            opacity: 0;
            animation: fadeIn 0.8s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
 /* Styling dropdown jumlah data (5, 10, 15) */
 .dropdown-menu {
        min-width: 5rem; /* Lebar dropdown secukupnya */
    }

    .dropdown-item {
        cursor: pointer;
        font-size: 14px; /* Perbesar sedikit */
    }

    /* Styling tombol dropdown */
    .dropdown-toggle {
        width: auto; /* Otomatis menyesuaikan ukuran */
        text-align: center;
        font-size: 14px; /* Ukuran teks agar konsisten */
        background-color: #e6b800; /* Warna kuning */
        color: white;
        border: none;
        border-radius: 4px;
    }

    /* Penyesuaian untuk filter status (Lihat Semua, Dipinjam, Dikembalikan) */
    .dropdown button#filterStatus {
        width: auto; /* Menyesuaikan konten */
        min-width: 140px; /* Ukuran minimum agar cukup */
        text-align: left; 
    }

    .summary-box-container {
        display: flex;
        justify-content: flex-end; /* Pindah ke kanan */
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }

    /* Summary box untuk Dikembalikan dan Dipinjam */
    .summary-box {
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        min-width: 170px; 
        text-align: center;
        color: #fff; /* Warna teks putih agar lebih kontras */
        background-color: #e6b800; /* Warna kuning */
        border: 1px solid #d4a000;
    }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1>Data Peminjaman Buku Tanah</h1>

    <div class="top-actions mb-3">
        <a href="index.php" class="btn btn-secondary">Kembali</a>
        <a href="form-bt.php" class="btn btn-success">+ Tambah Input Baru</a>
        <form action="laporan.php" method="GET" class="mb-3 d-flex justify-content-end">
    <button type="submit" class="btn btn-success">
    🖨 Cetak Laporan
    </button>
</form>
    </div>

    <!-- Pencarian -->
    <input type="text" id="searchInput" class="form-control search-input animated-fade mb-3" placeholder="Cari berdasarkan Nama atau Jenis Hak" value="<?php echo htmlspecialchars($search_term); ?>" oninput="searchData()">

    <div class="summary-box-container animated-fade">
    <!-- Dropdown Filter Status -->
    <div class="dropdown">
    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filterStatus" data-bs-toggle="dropdown" aria-expanded="false">
    <?php 
            $status_filter = $_GET['status'] ?? 'all';
            if ($status_filter == 'all') {
                echo '📑 Lihat Semua';
            } elseif ($status_filter == 'Dipinjam') {
                echo '📕 Dipinjam';
            } elseif ($status_filter == 'Dikembalikan') {
                echo '📗 Dikembalikan';
            }
        ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="filterStatus">
        <li><a class="dropdown-item" href="?<?php echo http_build_query(['limit' => $_GET['limit'] ?? 5, 'page' => 1, 'search' => $search_term]); ?>">📑 Lihat Semua</a></li>
        <li><a class="dropdown-item" href="?<?php echo http_build_query(['status' => 'Dipinjam', 'limit' => $_GET['limit'] ?? 5, 'page' => 1, 'search' => $search_term]); ?>">📕 Dipinjam</a></li>
        <li><a class="dropdown-item" href="?<?php echo http_build_query(['status' => 'Dikembalikan', 'limit' => $_GET['limit'] ?? 5, 'page' => 1, 'search' => $search_term]); ?>">📗 Dikembalikan</a></li>
    </ul>
</div>

    <!-- Kolom Dikembalikan dan Dipinjam -->
    <div class="summary-box">
        <strong>Dikembalikan:</strong> <?php echo $dikembalikan_result['dikembalikan']; ?>
    </div>
    <div class="summary-box">
        <strong>Dipinjam:</strong> <?php echo $dipinjam_result['dipinjam']; ?>
    </div>
</div>




    <!-- Dropdown Pilihan 5, 10, 15 -->
    <div class="dropdown mt-3">
        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo isset($_GET['limit']) ? $_GET['limit'] : 5; ?>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="?<?php echo http_build_query(['limit' => 5, 'page' => 1, 'search' => $search_term, 'status' => $_GET['status'] ?? 'all']); ?>">5</a></li>
            <li><a class="dropdown-item" href="?<?php echo http_build_query(['limit' => 10, 'page' => 1, 'search' => $search_term, 'status' => $_GET['status'] ?? 'all']); ?>">10</a></li>
            <li><a class="dropdown-item" href="?<?php echo http_build_query(['limit' => 15, 'page' => 1, 'search' => $search_term, 'status' => $_GET['status'] ?? 'all']); ?>">15</a></li>
        </ul>
    </div>
</div>

</div>


    <!-- Tabel Peminjaman -->
    <div class="table-container">
        <table class="table table-striped table-bordered text-center" id="peminjamanTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Tanda Tangan</th>
                    <th class="wide-col">Tanggal Pinjam</th>
                    <th class="wide-col">Waktu Dipinjam</th>
                    <th>Tanggal Kembali</th>
                    <th class="jenis-hak-col">Jenis Hak</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>File Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php 
        if ($result->num_rows > 0) {
            $no = $offset + 1;
            while ($row = $result->fetch_assoc()) {
                $waktu_dipinjam = $row['status'] == 'Dipinjam' ? date('Y-m-d H:i', strtotime($row['created_at'])) : 'Selesai';
                $tanggal_kembali = !empty($row['tanggal_kembali']) && $row['tanggal_kembali'] != '0000-00-00' ? date('Y-m-d', strtotime($row['tanggal_kembali'])) : '-';

                echo "<tr id='row_{$row['id']}' class='animated-fade'>
                    <td>{$no}</td>
                    <td>" . htmlspecialchars($row['nama_peminjam']) . "</td>
                    <td>" . (!empty($row['tanda_tangan']) ? "<img src='" . htmlspecialchars($row['tanda_tangan']) . "' width='100'>" : "-") . "</td>
                    <td>{$row['tanggal_pinjam']}</td>
                    <td>{$waktu_dipinjam}</td>
                    <td>{$tanggal_kembali}</td>
                    <td>" . htmlspecialchars($row['jenis_hak']) . "</td>
                    <td class='status-aksi " . ($row['status'] == 'Dikembalikan' ? 'dikembalikan' : 'peminjaman') . "'>{$row['status']}</td>
                    <td>" . htmlspecialchars($row['keterangan']) . "</td>
                    <td>" . (!empty($row['file_keterangan']) ? "<a href='uploads/" . htmlspecialchars($row['file_keterangan']) . "' target='_blank'>Lihat</a>" : "-") . "</td>
                    <td>
                        <a href='edit_peminjaman.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm btn-action'>Edit</a>";

                // Pastikan role sudah di-trim dan dibandingkan dengan benar
                if (trim($role) == 'Pimpinan Kantor') {
                    echo " <button class='btn btn-danger btn-sm btn-action' onclick='deleteRow(" . json_encode($row['id']) . ")'>Hapus</button>";
                }

                echo "</td></tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='11'>Tidak ada data peminjaman</td></tr>";
        }
        ?>

            </tbody>
        </table>
    </div>

<!-- Pagination -->
<div class="d-flex justify-content-between align-items-center" style="max-width: 87%; margin: 0 auto;">
    <div>
        <p style="margin-bottom: 5px;">Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $limit, $total_data); ?> of <?php echo $total_data; ?> entries</p>
    </div>
    <div>
        <ul class="pagination">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?<?php echo http_build_query(['limit' => $limit, 'page' => max(1, $page - 1), 'search' => $search_term, 'status' => $status_filter]); ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(['limit' => $limit, 'page' => $i, 'search' => $search_term, 'status' => $status_filter]); ?>"><?php echo $i; ?></a>
                </li>
            <?php } ?>
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?<?php echo http_build_query(['limit' => $limit, 'page' => min($total_pages, $page + 1), 'search' => $search_term, 'status' => $status_filter]); ?>">Next</a>
            </li>
        </ul>
    </div>
</div>


</div>

<script>
    let debounceTimer;

    function searchData() {
        var searchTerm = document.getElementById('searchInput').value;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('search', searchTerm);
            currentUrl.searchParams.set('page', 1); 
            window.location.href = currentUrl.toString();
        }, 500);
    }
    function deleteRow(id) {
    if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        window.location.href = 'hapus_peminjaman.php?id=' + id;
    }
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
