<?php
include 'koneksi.php';

$filterRole = isset($_GET['role']) ? $_GET['role'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;
$start = ($page - 1) * $perPage;

$query = "SELECT id, nama, email, role, verifikasi FROM user WHERE 1";
if ($filterRole) $query .= " AND role = '$filterRole'";
if ($search) $query .= " AND nama LIKE '%$search%'";
$query .= " LIMIT $start, $perPage";
$result = mysqli_query($conn, $query);

// Hitung total data
$totalQuery = "SELECT COUNT(*) as total FROM user WHERE 1";
if ($filterRole) $totalQuery .= " AND role = '$filterRole'";
if ($search) $totalQuery .= " AND nama LIKE '%$search%'";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];
$totalPage = ceil($totalData / $perPage);

// Bangun tabel data
$table = '<table>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Verifikasi</th>
            </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $table .= '<tr>
                    <td>' . $row['nama'] . '</td>
                    <td>' . $row['email'] . '</td>
                    <td>
                        <select class="role-dropdown" data-id="' . $row['id'] . '">
                            <option value="Admin" ' . ($row['role'] == 'Admin' ? 'selected' : '') . '>Admin</option>
                            <option value="Bagian BT" ' . ($row['role'] == 'Bagian BT' ? 'selected' : '') . '>Bagian BT</option>
                            <option value="Bagian SU" ' . ($row['role'] == 'Bagian SU' ? 'selected' : '') . '>Bagian SU</option>
                            <option value="Bagian Warkah" ' . ($row['role'] == 'Bagian Warkah' ? 'selected' : '') . '>Bagian Warkah</option>
                            <option value="Umum" ' . ($row['role'] == 'Umum' ? 'selected' : '') . '>Umum</option>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" class="verify-checkbox" data-id="' . $row['id'] . '" ' . ($row['verifikasi'] ? 'checked' : '') . '>
                    </td>
                </tr>';
}
$table .= '</table>';

// Bangun pagination
$pagination = '';
for ($i = 1; $i <= $totalPage; $i++) {
    $pagination .= '<a href="javascript:void(0);" onclick="fetchData(' . $i . ')">' . $i . '</a> ';
}

echo json_encode(['table' => $table, 'pagination' => $pagination]);
?>
