<?php
session_start();

// Proteksi Sesi Login Khusus Admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$user_login = $_SESSION['user'];
$file_json = 'data.json';
$data_peminjaman = [];

// Membaca Data dari data.json
if (file_exists($file_json)) {
    $json_string = file_get_contents($file_json);
    $data_peminjaman = json_decode($json_string, true) ?: [];
}

// --- LOGIKA AKSI: HAPUS / SETUJUI / TOLAK ---
if (isset($_GET['aksi']) && isset($_GET['index'])) {
    $index = (int)$_GET['index'];
    
    if (isset($data_peminjaman[$index])) {
        if ($_GET['aksi'] === 'hapus') {
            array_splice($data_peminjaman, $index, 1);
        } elseif ($_GET['aksi'] === 'setujui') {
            $data_peminjaman[$index]['status'] = 'Disetujui';
        } elseif ($_GET['aksi'] === 'tolak') {
            $data_peminjaman[$index]['status'] = 'Ditolak';
        }

        // Simpan perubahan kembali ke data.json
        file_put_contents($file_json, json_encode($data_peminjaman, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: admin.php');
        exit;
    }
}

// Array nama hari dan bulan dalam Bahasa Indonesia
$nama_hari = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];

$nama_bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// Mengurutkan data berdasarkan tanggal & waktu
usort($data_peminjaman, function($a, $b) {
    return strtotime($a['tanggal'] . ' ' . $a['jam_mulai']) <=> strtotime($b['tanggal'] . ' ' . $b['jam_mulai']);
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Pengelola - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header Navbar -->
    <nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold" href="admin.php">⚙️ Dashboard Pengelola / Admin</a>
            <div class="d-flex align-items-center text-white gap-3">
                <span class="small">👨‍💼 Administrator: <strong><?= htmlspecialchars($user_login['nama']); ?></strong></span>
                <a href="index.php" class="btn btn-outline-light btn-sm">🌐 Mode Siswa</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout 🚪</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow border-0 p-4" style="border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark mb-0">📋 Data Rekapitulasi Peminjaman</h3>
                <a href="kalender.php" class="btn btn-outline-primary btn-sm">📅 Cek Kalender</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle border">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col">Hari & Tanggal</th>
                            <th scope="col">Bulan & Tahun</th>
                            <th scope="col">Nama Peminjam</th>
                            <th scope="col">Ruangan / Fasilitas</th>
                            <th scope="col" class="text-center">Waktu Peminjaman</th>
                            <th scope="col" class="text-center">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data_peminjaman)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data peminjaman yang masuk.</td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            foreach ($data_peminjaman as $index => $item): 
                                $time_stamp = strtotime($item['tanggal']);
                                $hari_en = date('l', $time_stamp);
                                $hari_id = $nama_hari[$hari_en] ?? $hari_en;
                                $tgl = date('d', $time_stamp);
                                $bln_num = (int)date('n', $time_stamp);
                                $bln_id = $nama_bulan[$bln_num] ?? '';
                                $thn = date('Y', $time_stamp);
                                $status = $item['status'] ?? 'Disetujui';
                            ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $index + 1; ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark"><?= $hari_id; ?></span>
                                        <strong><?= $tgl; ?></strong>
                                    </td>
                                    <td><?= $bln_id; ?> <?= $thn; ?></td>
                                    <td><strong class="text-primary"><?= htmlspecialchars($item['nama']); ?></strong></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($item['ruangan']); ?></span></td>
                                    <td class="text-center">
                                        <code><?= htmlspecialchars($item['jam_mulai']); ?> - <?= htmlspecialchars($item['jam_selesai']); ?></code>
                                    </td>
                                    <td class="text-center">
                                        <!-- Badge Status -->
                                        <?php if ($status === 'Disetujui'): ?>
                                            <span class="badge bg-success mb-2">✔ Disetujui</span>
                                        <?php elseif ($status === 'Ditolak'): ?>
                                            <span class="badge bg-danger mb-2">✖ Ditolak</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark mb-2">⏳ Menunggu</span>
                                        <?php endif; ?>

                                        <!-- Tombol Aksi -->
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="admin.php?aksi=setujui&index=<?= $index; ?>" class="btn btn-sm btn-outline-success py-0 px-2" title="Setujui">✔</a>
                                            <a href="admin.php?aksi=tolak&index=<?= $index; ?>" class="btn btn-sm btn-outline-warning py-0 px-2" title="Tolak">✖</a>
                                            <a href="admin.php?aksi=hapus&index=<?= $index; ?>" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="return confirm('Yakin ingin menghapus jadwal peminjaman ini?')" title="Hapus">🗑</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
