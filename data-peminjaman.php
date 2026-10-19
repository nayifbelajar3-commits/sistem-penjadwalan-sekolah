<?php
// Membaca data dari data.json
$file_json = 'data.json';
$data_peminjaman = [];

if (file_exists($file_json)) {
    $json_string = file_get_contents($file_json);
    $data_peminjaman = json_decode($json_string, true) ?: [];
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

// Mengurutkan data berdasarkan tanggal (paling baru / mendatang lebih dulu)
usort($data_peminjaman, function($a, $b) {
    return strtotime($a['tanggal'] . ' ' . $a['jam_mulai']) <=> strtotime($b['tanggal'] . ' ' . $b['jam_mulai']);
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peminjaman Ruangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🏫 Panel Pengelola - Penjadwalan Sekolah</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">⬅ Kembali ke Utama</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow border-0 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold text-dark mb-0">📋 Rekapitulasi Data Peminjaman</h3>
                <a href="form-pinjam.php" class="btn btn-success btn-sm">+ Tambah Peminjaman</a>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data_peminjaman)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data peminjaman yang tersimpan.</td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $no = 1;
                            foreach ($data_peminjaman as $item): 
                                $time_stamp = strtotime($item['tanggal']);
                                $hari_en = date('l', $time_stamp);
                                $hari_id = $nama_hari[$hari_en] ?? $hari_en;
                                $tgl = date('d', $time_stamp);
                                $bln_num = (int)date('n', $time_stamp);
                                $bln_id = $nama_bulan[$bln_num] ?? '';
                                $thn = date('Y', $time_stamp);
                            ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $no++; ?></td>
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
