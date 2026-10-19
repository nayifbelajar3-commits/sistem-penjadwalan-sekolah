<?php
session_start();

// Proteksi Sesi Login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_login = $_SESSION['user'];
$error_bentrok = '';
$sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama        = trim($_POST['nama'] ?? '');
    $ruangan     = trim($_POST['ruangan'] ?? '');
    $tanggal     = trim($_POST['tanggal'] ?? '');
    $jam_mulai   = trim($_POST['jam_mulai'] ?? '');
    $jam_selesai = trim($_POST['jam_selesai'] ?? '');

    $file_json = 'data.json';
    $data_peminjaman = [];

    if (file_exists($file_json)) {
        $json_string = file_get_contents($file_json);
        $data_peminjaman = json_decode($json_string, true) ?: [];
    }

    // --- LOGIKA CEK BENTROK JADWAL YANG KETAT ---
    $mulai_baru   = strtotime("$tanggal $jam_mulai");
    $selesai_baru = strtotime("$tanggal $jam_selesai");

    $is_bentrok = false;
    $peminjam_bentrok = '';

    foreach ($data_peminjaman as $item) {
        // Abaikan jika data dibatalkan/ditolak (jika ada sistem status)
        $status_item = $item['status'] ?? 'Disetujui';
        if ($status_item === 'Ditolak') {
            continue;
        }

        // Cek jika ruangan dan tanggal persis sama
        if (trim($item['ruangan']) === trim($ruangan) && trim($item['tanggal']) === trim($tanggal)) {
            $mulai_lama   = strtotime($item['tanggal'] . ' ' . $item['jam_mulai']);
            $selesai_lama = strtotime($item['tanggal'] . ' ' . $item['jam_selesai']);

            // Rumus tumpang tindih waktu (Overlap) yang akurat:
            // (Mulai Baru < Selesai Lama) DAN (Selesai Baru > Mulai Lama)
            if ($mulai_baru < $selesai_lama && $selesai_baru > $mulai_lama) {
                $is_bentrok = true;
                $peminjam_bentrok = $item['nama'];
                break;
            }
        }
    }

    if ($is_bentrok) {
        $error_bentrok = "⚠️ <strong>JADWAL BENTROK!</strong> Ruangan <strong>" . htmlspecialchars($ruangan) . "</strong> pada tanggal tersebut sudah dipinjam oleh <strong>" . htmlspecialchars($peminjam_bentrok) . "</strong> pada rentang jam yang bersinggungan.";
    } else {
        // Simpan data baru ke array
        $data_peminjaman[] = [
            'id'          => uniqid(),
            'nama'        => $nama,
            'ruangan'     => $ruangan,
            'tanggal'     => $tanggal,
            'jam_mulai'   => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'status'      => 'Disetujui'
        ];

        // Simpan kembali ke data.json dengan rapi
        file_put_contents($file_json, json_encode($data_peminjaman, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $sukses = "🎉 Peminjaman berhasil diajukan dan jadwal disetujui tanpa bentrok!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header Navbar -->
    <nav class="navbar navbar-dark bg-success shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold" href="index.php">🏫 Layanan Peminjaman Sekolah</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">⬅ Kembali ke Utama</a>
        </div>
    </nav>

    <div class="container py-3">
        <div class="card shadow border-0 p-4 mx-auto" style="max-width: 550px; border-radius: 15px;">
            <h3 class="fw-bold text-center text-success mb-3">📝 Form Pengajuan Peminjaman</h3>

            <!-- Pesan Notifikasi Sukses / Bentrok -->
            <?php if ($error_bentrok): ?>
                <div class="alert alert-danger shadow-sm mb-3"><?= $error_bentrok; ?></div>
            <?php endif; ?>

            <?php if ($sukses): ?>
                <div class="alert alert-success shadow-sm mb-3"><?= $sukses; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Peminjam / Guru / Kelas</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user_login['nama']); ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Ruangan / Fasilitas</label>
                    <select name="ruangan" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Ruangan --</option>
                        <option value="Laboratorium Komputer">Laboratorium Komputer</option>
                        <option value="Aula Utama">Aula Utama</option>
                        <option value="Lapangan Olahraga">Lapangan Olahraga</option>
                        <option value="Ruang Perpustakaan">Ruang Perpustakaan</option>
                        <option value="Ruang Multimedia">Ruang Multimedia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Peminjaman</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label fw-bold">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                    Ajukan Peminjaman
                </button>
            </form>
        </div>
    </div>

</body>
</html>
