<?php
session_start();

// PROTEKSI KETAT: Jika belum login ATAU role BUKAN 'admin', lempar keluar!
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$user_login = $_SESSION['user'];
$file_json = 'data.json';
$data_peminjaman = [];

if (file_exists($file_json)) {
    $json_string = file_get_contents($file_json);
    $data_peminjaman = json_decode($json_string, true) ?: [];
}

// Logika Hapus / Setujui / Tolak
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

        file_put_contents($file_json, json_encode($data_peminjaman, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: admin.php');
        exit;
    }
}

$nama_hari = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];

$nama_bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Pengelola - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

    <nav class="navbar navbar-dark bg-secondary shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold" href="admin.php">⚙️ Dashboard Administrator (Guru / Pengelola)</a>
            <div class="d-flex align-items-center gap-3">
                <span class="small">👨‍💼 Admin: <strong><?= htmlspecialchars($user_login['nama']); ?></strong></span>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout 🚪</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card bg-secondary text-white shadow border-0 p-4" style="border-radius: 12px; --bs-bg-opacity: .2;">
            <h3 class="fw-bold mb-4">📋 Rekapitulasi Peminjaman Fasilitas</h3>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle border-secondary">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col">Hari & Tanggal</th>
                            <th scope="col">Bulan & Tahun</th>
                            <th scope="col">Nama Peminjam</th>
                            <th scope="col">Ruangan</th>
                            <th scope="col" class="text-center">Waktu</th>
                            <th scope="col" class="text-center">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data_peminjaman)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada peminjaman.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data_peminjaman as $index => $item): 
                                $time_stamp = strtotime($item['tanggal']);
                                $hari_id = $nama_hari[date('l', $time_stamp)] ?? '';
                                $tgl = date('d', $time_stamp);
                                $bln_id = $nama_bulan[(int)date('n', $time_stamp)] ?? '';
                                $thn = date('Y', $time_stamp);
                                $status = $item['status'] ?? 'Disetujui';
                            ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $index + 1; ?></td>
                                    <td><span class="badge bg-warning text-dark"><?= $hari_id; ?></span> <strong><?= $tgl; ?></strong></td>
                                    <td><?= $bln_id; ?> <?= $thn; ?></td>
                                    <td><strong><?= htmlspecialchars($item['nama']); ?></strong></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($item['ruangan']); ?></span></td>
                                    <td class="text-center"><code><?= htmlspecialchars($item['jam_mulai']); ?> - <?= htmlspecialchars($item['jam_selesai']); ?></code></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $status === 'Disetujui' ? 'success' : ($status === 'Ditolak' ? 'danger' : 'warning'); ?> mb-2">
                                            <?= $status; ?>
                                        </span>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="admin.php?aksi=setujui&index=<?= $index; ?>" class="btn btn-sm btn-outline-success py-0 px-2">✔</a>
                                            <a href="admin.php?aksi=tolak&index=<?= $index; ?>" class="btn btn-sm btn-outline-warning py-0 px-2">✖</a>
                                            <a href="admin.php?aksi=hapus&index=<?= $index; ?>" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="return confirm('Hapus data ini?')">🗑</a>
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
