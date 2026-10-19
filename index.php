<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Ambil data pengguna dari sesi
$user_login = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penjadwalan Sekolah - Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header Navbar -->
    <nav class="navbar navbar-dark bg-success shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold" href="index.php">🏫 Layanan Peminjaman Fasilitas Sekolah</a>
            <div class="d-flex align-items-center text-white gap-3">
                <span class="small">👤 Halo, <strong><?= htmlspecialchars($user_login['nama']); ?></strong></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout 🚪</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container text-center py-5">
        <div class="card shadow border-0 p-5 mx-auto" style="max-width: 600px; border-radius: 15px;">
            <h2 class="fw-bold mb-3 text-success">Menu Peminjaman Ruangan</h2>
            <p class="text-muted mb-4">Silakan cek ketersediaan ruangan pada kalender sebelum mengajukan peminjaman fasilitas sekolah.</p>
            
            <div class="d-grid gap-3 col-11 mx-auto">
                <a href="kalender.php" class="btn btn-primary btn-lg shadow-sm py-3 fw-bold">
                    📅 Lihat Jadwal Kalender
                </a>
                <a href="form-pinjam.php" class="btn btn-success btn-lg shadow-sm py-3 fw-bold">
                    ➕ Ajukan Peminjaman Ruangan
                </a>
                <?php if ($user_login['role'] === 'admin'): ?>
                    <a href="admin.php" class="btn btn-dark btn-lg shadow-sm py-2 fw-bold mt-2">
                        ⚙️ Masuk Panel Admin
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
