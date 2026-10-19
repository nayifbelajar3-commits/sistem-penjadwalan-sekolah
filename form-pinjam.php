<?php
$pesan = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $ruangan = $_POST['ruangan'];
    $tanggal = $_POST['tanggal'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Lokasi file JSON untuk menyimpan data
    $file_json = 'data.json';
    $data_peminjaman = [];

    if (file_exists($file_json)) {
        $json_string = file_get_contents($file_json);
        $data_peminjaman = json_decode($json_string, true) ?: [];
    }

    // Cek Bentrok (Anti-Collision)
    $bentrok = false;
    foreach ($data_peminjaman as $item) {
        if ($item['ruangan'] == $ruangan && $item['tanggal'] == $tanggal) {
            // Cek apakah jam bertabrakan
            if (($jam_mulai < $item['jam_selesai']) && ($jam_selesai > $item['jam_mulai'])) {
                $bentrok = true;
                break;
            }
        }
    }

    if ($bentrok) {
        $pesan = "Maaf, jadwal bentrok! Ruangan $ruangan sudah dipesan pada tanggal dan jam tersebut.";
        $status = "danger";
    } else {
        // Simpan data baru
        $peminjaman_baru = [
            'nama' => $nama,
            'ruangan' => $ruangan,
            'tanggal' => $tanggal,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai
        ];
        
        $data_peminjaman[] = $peminjaman_baru;
        file_put_contents($file_json, json_encode($data_peminjaman, JSON_PRETTY_PRINT));

        $pesan = "Peminjaman berhasil diajukan dan disimpan!";
        $status = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Peminjaman Fasilitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🏫 Penjadwalan Sekolah</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">⬅ Kembali ke Utama</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0 p-4">
                    <h3 class="mb-3 text-center text-success">📝 Form Pengajuan Peminjaman</h3>
                    
                    <?php if (!empty($pesan)): ?>
                        <div class="alert alert-<?php echo $status; ?>" role="alert">
                            <?php echo $pesan; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Peminjam / Guru / Kelas</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Budi (Kelas 8A)" required>
                        </div>

                        <div class="mb-3">
                            <label for="ruangan" class="form-label">Pilih Ruangan / Fasilitas</label>
                            <select class="form-select" id="ruangan" name="ruangan" required>
                                <option value="" selected disabled>-- Pilih Ruangan --</option>
                                <option value="Lab Komputer">Laboratorium Komputer</option>
                                <option value="Aula Utama">Aula Utama</option>
                                <option value="Bilik Sukan">Ruang Serbaguna / Olahraga</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Peminjaman</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
