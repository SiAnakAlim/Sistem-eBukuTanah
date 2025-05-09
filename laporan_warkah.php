<?php
require 'koneksi.php';

// Ambil filter dari URL atau default
$status = $_GET['status'] ?? 'all';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Query dengan filter status dan tanggal
$where = [];
if ($status !== 'all') {
    $where[] = "status = '$status'";
}
if (!empty($start_date) && !empty($end_date)) {
    $where[] = "tanggal_pinjam BETWEEN '$start_date' AND '$end_date'";
}
$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$query = "SELECT nama_peminjam, tanggal_pinjam, jenis_hak, status, keterangan 
          FROM peminjaman_warkah $where_sql ORDER BY tanggal_pinjam ASC";
$result = $conn->query($query);
?>
<?php
require 'auth.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />

    <style>
        body { padding: 20px; font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { background: #ffffff; padding: 25px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #007bff; font-weight: bold; margin-bottom: 20px; }
        .form-group label { font-weight: bold; color: #333; }
        .form-control { border-radius: 5px; }
        .btn-custom { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; transition: 0.3s; }
        .btn-custom:hover { background: #218838; }
        .btn-primary { margin-top: 10px; transition: 0.3s; }
        .btn-primary:hover { background: #0056b3; }
        table { margin-top: 20px; border-collapse: collapse; width: 100%; background: white; border-radius: 5px; }
        thead { background: #007bff; color: white; }
        th, td { padding: 12px; text-align: center; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .status-pinjam { color: red; font-weight: bold; }
        .status-kembali { color: green; font-weight: bold; }
        .filter-container { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <h2>📋 Laporan Peminjaman Warkah</h2>

    <div class="filter-container">
    <form method="GET" id="filterForm">
        <div class="row align-items-end">
            <div class="col-md-3 form-group">
                <label>Status:</label>
                <select name="status" id="statusFilter" class="form-control">
                    <option value="all" <?= ($status === 'all') ? 'selected' : '' ?>>Semua</option>
                    <option value="Dipinjam" <?= ($status === 'Dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                    <option value="Dikembalikan" <?= ($status === 'Dikembalikan') ? 'selected' : '' ?>>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label>Tanggal Awal:</label>
                <input type="date" name="start_date" id="startDate" class="form-control" value="<?= $start_date ?>">
            </div>
            <div class="col-md-3 form-group">
                <label>Tanggal Akhir:</label>
                <input type="date" name="end_date" id="endDate" class="form-control" value="<?= $end_date ?>">
            </div>
            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary me-2">🔍 Filter</button>
                <a href="index.php" class="btn btn-secondary">🔙 Kembali</a>
            </div>
        </div>
    </form>
</div>


    <!-- Tombol Simpan PDF -->
    <button id="downloadPDF" class="btn btn-custom">📄 Simpan sebagai PDF</button>

    <!-- Tabel Data -->
    <table class="table table-bordered" id="laporanTable">
        <thead>
            <tr>
                <th>Nama Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>nomor 208</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody id="dataTabel">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></td>
                    <td><?= htmlspecialchars($row['jenis_hak']) ?></td>
                    <td>
                        <?php if (trim($row['status']) === 'Dikembalikan') { ?>
                            <span class="status-kembali">Dikembalikan</span>
                        <?php } else { ?>
                            <span class="status-pinjam">Dipinjam</span>
                        <?php } ?>
                    </td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('downloadPDF').addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        doc.text("Laporan Peminjaman Buku Tanah", 14, 15);
        doc.autoTable({
            startY: 20,
            head: [['Nama Peminjam', 'Tanggal Pinjam', 'Jenis Hak', 'Status', 'Keterangan']],
            body: Array.from(document.querySelectorAll("#laporanTable tbody tr")).map(row => 
                Array.from(row.cells).map(cell => cell.innerText)
            ),
            theme: 'grid',
            styles: { fontSize: 10 },
            headStyles: { fillColor: [0, 123, 255] },
        });

        doc.save("Laporan_Peminjaman.pdf");
    });
</script>

</body>
</html>
