<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil role user yang login
$user_id = $_SESSION['user_id'];
$query_role = "SELECT role FROM user WHERE id = '$user_id'";
$result_role = mysqli_query($conn, $query_role);
$user_role_data = mysqli_fetch_assoc($result_role);
$user_role = $user_role_data['role'];

$filterRole = isset($_GET['role']) ? $_GET['role'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$perPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

$query = "SELECT id, nama, email, role, verifikasi FROM user WHERE 1";
if ($filterRole) $query .= " AND role = '$filterRole'";
if ($search) $query .= " AND nama LIKE '%$search%'";
$query .= " LIMIT $start, $perPage";
$result = mysqli_query($conn, $query);

$totalQuery = "SELECT COUNT(*) as total FROM user WHERE 1";
if ($filterRole) $totalQuery .= " AND role = '$filterRole'";
if ($search) $totalQuery .= " AND nama LIKE '%$search%'";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];
$totalPage = ceil($totalData / $perPage);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />
    <title>Pengaturan Akun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .filters {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        select, input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #e6b800;
            color: white;
        }
        .role-dropdown, .verify-checkbox {
            font-size: 14px;
            padding: 6px;
            border-radius: 6px;
            width: 100%;
            border: 1px solid #ccc;
        }
        .verify-checkbox {
            width: 20px;
            height: 20px;
        }
        .pagination {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }
        .pagination a {
            padding: 8px 12px;
            margin: 0 4px;
            border-radius: 5px;
            text-decoration: none;
            background: #ddd;
            color: black;
            transition: background 0.3s;
        }
        .pagination a:hover {
            background: #e6b800;
            color: white;
        }
        .back-button {
    padding: 8px 12px;
    background: #e6b800;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s, transform 0.2s;
    position: absolute;
    right: 20px;
    top: 20px;
}

.back-button:hover {
    background: #cc9a00;
    transform: scale(1.05);
}

    </style>
</head>
<body>

<div class="container" style="position: relative;">
    <a href="index.php" class="back-button">&larr; Kembali</a>
    <h2>Pengaturan Akun</h2>

    <div class="filters">
        <label for="role">Filter Role:</label>
        <select name="role" id="role" onchange="filterData()">
            <option value="">Semua</option>
            <option value="Pimpinan Kantor" <?= ($filterRole == 'Pimpinan Kantor') ? 'selected' : '' ?>>Pimpinan Kantor</option>
            <option value="Admin" <?= ($filterRole == 'Admin') ? 'selected' : '' ?>>Admin</option>
            <option value="Bagian BT" <?= ($filterRole == 'Bagian BT') ? 'selected' : '' ?>>Bagian BT</option>
            <option value="Bagian SU" <?= ($filterRole == 'Bagian SU') ? 'selected' : '' ?>>Bagian SU</option>
            <option value="Bagian Warkah" <?= ($filterRole == 'Bagian Warkah') ? 'selected' : '' ?>>Bagian Warkah</option>
            <option value="Umum" <?= ($filterRole == 'Umum') ? 'selected' : '' ?>>Umum</option>
        </select>

        <input type="text" id="search" placeholder="Cari nama..." value="<?= $search ?>" onkeyup="delayedSearch()">
    </div>

    <table>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Verifikasi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
    <select class="role-dropdown" data-id="<?= $row['id'] ?>" <?= ($user_role !== 'Admin' && $user_role !== 'Pimpinan Kantor') ? 'disabled' : '' ?>>
        <option value="Pimpinan Kantor" <?= ($row['role'] == 'Pimpinan Kantor') ? 'selected' : '' ?>>Pimpinan Kantor</option>
        <option value="Admin" <?= ($row['role'] == 'Admin') ? 'selected' : '' ?>>Admin</option>
        <option value="Bagian BT" <?= ($row['role'] == 'Bagian BT') ? 'selected' : '' ?>>Bagian BT</option>
        <option value="Bagian SU" <?= ($row['role'] == 'Bagian SU') ? 'selected' : '' ?>>Bagian SU</option>
        <option value="Bagian Warkah" <?= ($row['role'] == 'Bagian Warkah') ? 'selected' : '' ?>>Bagian Warkah</option>
        <option value="Umum" <?= ($row['role'] == 'Umum') ? 'selected' : '' ?>>Umum</option>
    </select>
</td>


            <td>
                <input type="checkbox" class="verify-checkbox" data-id="<?= $row['id'] ?>" <?= $row['verifikasi'] ? 'checked' : '' ?>>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
            <a href="?page=<?= $i ?>&role=<?= $filterRole ?>&search=<?= $search ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>

<script>
document.querySelectorAll('.role-dropdown').forEach(select => {
    select.addEventListener('change', function() {
        let userId = this.getAttribute('data-id');
        let newRole = this.value;
        if (confirm('Apakah Anda yakin ingin mengubah role pengguna ini menjadi ' + newRole + '?')) {
            fetch('update_role.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${userId}&role=${newRole}`
            })
            .then(response => response.text())
            .then(data => alert(data));
        } else {
            location.reload();
        }
    });
});

document.querySelectorAll('.verify-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        let userId = this.getAttribute('data-id');
        let newStatus = this.checked ? 1 : 0;
        if (confirm('Apakah Anda yakin ingin mengubah status verifikasi?')) {
            fetch('update_verifikasi.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${userId}&verifikasi=${newStatus}`
            })
            .then(response => response.text())
            .then(data => alert(data));
        } else {
            location.reload();
        }
    });
});
</script>
<script>
let searchTimeout;
function delayedSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(filterData, 500);
}

function filterData() {
    let role = document.getElementById("role").value;
    let search = document.getElementById("search").value;
    window.location.href = `?role=${role}&search=${search}`;
}
</script>

</body>

</html>
