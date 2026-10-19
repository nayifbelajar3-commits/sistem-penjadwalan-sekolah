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
            <!-- Tempat Kalendar Ditampilkan -->
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
          events: [
            {
              title: 'Laboratorium Komputer (Kelas 8A)',
              start: '2026-10-20T08:00:00',
              end: '2026-10-20T10:00:00',
              color: '#dc3545'
            },
            {
              title: 'Aula Utama (Rapat Guru)',
              start: '2026-10-22T13:00:00',
              end: '2026-10-22T15:00:00',
              color: '#0d6efd'
            }
          ]
        });
        calendar.render();
      });
    </script>

</body>
</html>
