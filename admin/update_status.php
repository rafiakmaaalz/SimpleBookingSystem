<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../koneksi.php';
$id = $_GET['id'];
$status = $_GET['status'];
$allowed_statuses = ['confirmed', 'canceled'];
if (in_array($status, $allowed_statuses)) {
    $query = "UPDATE reservations SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    mysqli_stmt_execute($stmt);
}
header("Location: index.php");
exit();
?>