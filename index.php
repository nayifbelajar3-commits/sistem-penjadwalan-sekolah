<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_login = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Siswa - Penjadwalan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold" href="index.php">🎓 Portal Peminjaman Siswa</a>
            <div class="d-flex align-items-center gap-3 text-white">
                <span class="small">Halo, <strong><?= htmlspecialchars($user_login['nama']); ?></strong></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout 🚪</a>
            </div>
        </div>
    </nav>

    <div class="container text-center py-5">
        <h2 class="fw-bold text-success mb-3">Selamat Datang di Sistem Peminjaman Fasilitas</h2>
        <p class="text-muted mb-4">Silakan pilih menu di bawah untuk melihat kalender atau mengajukan peminjaman ruangan.</p>

        <div class="d-flex justify-content-center gap-3">
            <a href="form-pinjam.php" class="btn btn-success btn-lg shadow-sm">➕ Ajukan Peminjaman</a>
            <a href="kalender.php" class="btn btn-outline-success btn-lg shadow-sm">📅 Lihat Kalender Schedule</a>
        </div>
    </div>

</body>
</html>
