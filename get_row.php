<?php
require 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM peminjaman WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo json_encode($row);
}
?>
