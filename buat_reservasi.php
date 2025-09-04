<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// --- FUNGSI UNTUK MEMBUAT KALENDER ---
function build_calendar($month, $year) {
    // Array nama hari
    $daysOfWeek = array('Min','Sen','Sel','Rab','Kam','Jum','Sab');

    // Hari pertama dalam bulan
    $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

    // Jumlah hari dalam bulan
    $numberDays = date('t',$firstDayOfMonth);

    // Informasi tanggal hari pertama
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    // Navigasi bulan
    $prev_month = date('m', mktime(0, 0, 0, $month-1, 1, $year));
    $prev_year = date('Y', mktime(0, 0, 0, $month-1, 1, $year));
    $next_month = date('m', mktime(0, 0, 0, $month+1, 1, $year));
    $next_year = date('Y', mktime(0, 0, 0, $month+1, 1, $year));
    
    // Membuat tabel kalender
    $calendar = "<div class='calendar-container'>";
    $calendar .= "<div class='calendar-header'>
                    <a href='?month=$prev_month&year=$prev_year'>&laquo; Bulan Lalu</a>
                    <h2>$monthName $year</h2>
                    <a href='?month=$next_month&year=$next_year'>Bulan Depan &raquo;</a>
                  </div>";
    $calendar .= "<table class='calendar'>";
    $calendar .= "<tr>";

    // Header hari
    foreach($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    } 
    $calendar .= "</tr><tr>";

    // Mengisi sel kosong sebelum hari pertama
    if ($dayOfWeek > 0) { 
        for($k=0;$k<$dayOfWeek;$k++){
            $calendar .= "<td class='day not-current'></td>";
        }
    }
    
    $currentDay = 1;
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }
        
        $currentDate = "$year-$month-".str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $today = date("Y-m-d");
        $class = ($currentDate == $today) ? "today" : "";
        
        // Tandai tanggal yang sudah lewat agar tidak bisa diklik
        if ($currentDate < $today) {
            $calendar .= "<td class='day not-current'><div class='day-number'>$currentDay</div></td>";
        } else {
            $calendar .= "<td class='day clickable $class' data-date='$currentDate'><div class='day-number'>$currentDay</div></td>";
        }
        
        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) { 
        $remainingDays = 7 - $dayOfWeek;
        for($i=0;$i<$remainingDays;$i++){
            $calendar .= "<td class='day not-current'></td>";
        }
    }
    
    $calendar .= "</tr>";
    $calendar .= "</table>";
    $calendar .= "</div>";
    
    return $calendar;
}

// Mendapatkan bulan dan tahun saat ini atau dari GET parameter
$dateComponents = getdate();
$month = isset($_GET['month']) ? $_GET['month'] : $dateComponents['mon'];
$year = isset($_GET['year']) ? $_GET['year'] : $dateComponents['year'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Buat Reservasi</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <a href="index.php">&laquo; Kembali ke Dashboard</a>
    <h1>Buat Reservasi Baru</h1>
    
    <?php if (isset($_GET['error'])): ?>
        <p style="color:var(--danger); background: var(--bg-light); padding: 10px; border-radius: 5px;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <div class="booking-container">
        <div class="calendar-wrapper">
            <?php echo build_calendar($month, $year); ?>
        </div>
        
        <div class="form-wrapper">
            <div class="card">
                <form action="proses_reservasi.php" method="POST" id="booking-form">
                    <h2>Detail Reservasi</h2>
                    <p>Pilih tanggal pada kalender untuk memulai.</p>
                    
                    <div id="form-fields" style="display:none;">
                        <input type="hidden" name="tanggal_reservasi" id="tanggal_reservasi">
                        
                        <label>Tanggal Terpilih:</label>
                        <input type="text" id="tanggal_terpilih" readonly style="background:#2c3e50; font-weight:bold;">
                        
                        <label for="jumlah_tamu">Jumlah Tamu:</label>
                        <input type="number" name="jumlah_tamu" required min="1">

                        <label for="waktu_reservasi">Pilih Waktu:</label>
                        <select name="waktu_reservasi" id="waktu_reservasi" required>
                            <option value="08:00:00">08:00 (Makan Pagi)</option>
                            <option value="10:00:00">10:00 (Makan Pagi)</option>
                            <option value="13:00:00">13:00 (Makan Siang)</option>
                            <option value="18:00:00">18:00 (Makan Malam)</option>
                            <option value="22:00:00">22:00 (Makan Malam)</option>
                        </select>
                        <button type="submit">Konfirmasi Reservasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Modifikasi JavaScript untuk menyesuaikan dengan form baru
document.addEventListener('DOMContentLoaded', function() {
    const calendarDays = document.querySelectorAll('.calendar .day.clickable');
    const formFields = document.getElementById('form-fields');
    const tanggalInput = document.getElementById('tanggal_reservasi');
    const tanggalTerpilihDisplay = document.getElementById('tanggal_terpilih');
    const bookingForm = document.getElementById('booking-form');
    let selectedDay = null;

    calendarDays.forEach(day => {
        day.addEventListener('click', function() {
            if(selectedDay) {
                selectedDay.classList.remove('selected');
            }
            this.classList.add('selected');
            selectedDay = this;

            const selectedDate = this.getAttribute('data-date');
            tanggalInput.value = selectedDate;
            
            // Format tanggal agar lebih mudah dibaca (e.g., 04 September 2025)
            const dateObj = new Date(selectedDate + 'T00:00:00');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            tanggalTerpilihDisplay.value = dateObj.toLocaleDateString('id-ID', options);

            formFields.style.display = 'block';
            bookingForm.querySelector('p').style.display = 'none'; // Sembunyikan pesan awal
        });
    });
});
</script>
</body>
</html>