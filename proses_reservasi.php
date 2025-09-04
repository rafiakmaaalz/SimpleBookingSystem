<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Atur kapasitas maksimum per jam (misalnya, 3 reservasi)
$max_reservasi_per_jam = 3;

$user_id = $_SESSION['user_id'];
$jumlah_tamu = $_POST['jumlah_tamu'];
$tanggal_reservasi = $_POST['tanggal_reservasi']; // Dari input hidden kalender
$waktu_reservasi = $_POST['waktu_reservasi']; // Dari dropdown waktu

// Gabungkan tanggal dan waktu
$waktu_reservasi_full = $tanggal_reservasi . ' ' . $waktu_reservasi;

// --- VALIDASI DOUBLE BOOKING ---
// Tentukan awal dan akhir dari slot waktu satu jam
$slot_awal = date('Y-m-d H:i:s', strtotime($waktu_reservasi_full));
$slot_akhir = date('Y-m-d H:i:s', strtotime($waktu_reservasi_full . ' +59 minutes'));

// Query untuk menghitung reservasi yang sudah ada di slot waktu tersebut
$query_cek = "SELECT COUNT(id) as total FROM reservations 
              WHERE waktu_reservasi BETWEEN ? AND ? AND status != 'canceled'";
$stmt_cek = mysqli_prepare($koneksi, $query_cek);
mysqli_stmt_bind_param($stmt_cek, "ss", $slot_awal, $slot_akhir);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);
$data_cek = mysqli_fetch_assoc($result_cek);

if ($data_cek['total'] >= $max_reservasi_per_jam) {
    // Jika slot sudah penuh, kembalikan dengan pesan error
    header("Location: buat_reservasi.php?error=" . urlencode("Maaf, slot waktu yang Anda pilih sudah penuh."));
    exit();
}
// --- AKHIR VALIDASI ---

// Jika slot tersedia, lanjutkan proses insert
$query_insert = "INSERT INTO reservations (user_id, jumlah_tamu, waktu_reservasi) VALUES (?, ?, ?)";
$stmt_insert = mysqli_prepare($koneksi, $query_insert);
mysqli_stmt_bind_param($stmt_insert, "iis", $user_id, $jumlah_tamu, $waktu_reservasi_full);

if (mysqli_stmt_execute($stmt_insert)) {
    header("Location: reservasi_saya.php?status=sukses");
} else {
    echo "Error: Gagal membuat reservasi.";
}
?>