<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
    <p>Gunakan menu di bawah untuk mengelola reservasi Anda.</p>
    <ul>
        <li><a href="buat_reservasi.php">Buat Reservasi Baru</a></li>
        <li><a href="reservasi_saya.php">Lihat Riwayat Reservasi Saya</a></li>
    </ul>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>