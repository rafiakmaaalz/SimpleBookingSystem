<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reservasi Saya</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <a href="index.php">&laquo; Kembali ke Dashboard</a>
    <h1>Riwayat Reservasi Saya</h1>
    <table>
        <thead>
            <tr>
                <th>Waktu Reservasi</th>
                <th>Jumlah Tamu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM reservations WHERE user_id = ? ORDER BY waktu_reservasi DESC";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?php echo date('d M Y, H:i', strtotime($row['waktu_reservasi'])); ?></td>
                <td><?php echo $row['jumlah_tamu']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>