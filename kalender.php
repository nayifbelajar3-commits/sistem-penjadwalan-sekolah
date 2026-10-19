<?php
// Membaca data jadwal dari file JSON
$file_json = 'data.json';
$events = [];

if (file_exists($file_json)) {
    $json_string = file_get_contents($file_json);
    $data_peminjaman = json_decode($json_string, true) ?: [];

    // Format data agar sesuai dengan format FullCalendar.js
    foreach ($data_peminjaman as $item) {
        $events[] = [
            'title' => $item['ruangan'] . ' (' . $item['nama'] . ')',
            'start' => $item['tanggal'] . 'T' . $item['jam_mulai'],
            'end'   => $item['tanggal'] . 'T' . $item['jam_selesai'],
            'color' => '#0d6efd'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Peminjaman Fasilitas</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FullCalendar CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🏫 Penjadwalan Sekolah</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">⬅ Kembali ke Utama</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow border-0 p-4">
            <h3 class="mb-3 text-center text-primary">📅 Jadwal Penggunaan Ruangan & Fasilitas</h3>
            <hr>
            <!-- Tempat Kalender Ditampilkan -->
            <div id="calendar"></div>
        </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'id',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
          },
          // Mengambil data dinamis yang dibaca PHP dari data.json
          events: <?php echo json_encode($events); ?>
        });
        calendar.render();
      });
    </script>

</body>
</html>
