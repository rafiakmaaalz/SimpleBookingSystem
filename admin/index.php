<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Admin Panel - Semua Reservasi</h1>
    <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>! | <a href="../logout.php">Logout</a></p>
    <table>
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Waktu Reservasi</th>
                <th>Jumlah Tamu</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT r.id, u.nama, r.jumlah_tamu, r.waktu_reservasi, r.status 
                      FROM reservations r JOIN users u ON r.user_id = u.id
                      ORDER BY r.waktu_reservasi DESC";
            $result = mysqli_query($koneksi, $query);
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo date('d M Y, H:i', strtotime($row['waktu_reservasi'])); ?></td>
                <td><?php echo $row['jumlah_tamu']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <a href="update_status.php?id=<?php echo $row['id']; ?>&status=confirmed" class="action-link confirm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path></svg>
                            Confirm
                        </a>
                        <a href="update_status.php?id=<?php echo $row['id']; ?>&status=canceled" class="action-link cancel">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path></svg>
                            Cancel
                        </a>
                    <?php else:
                        // Menambahkan class warna sesuai status
                        $status_class = 'status-' . $row['status'];
                    ?>
                        <strong class="<?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></strong>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>