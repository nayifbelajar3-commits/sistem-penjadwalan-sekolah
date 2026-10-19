<?php
session_start();

$error = '';
$sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $file_users = 'users.json';
    $users = [];

    if (file_exists($file_users)) {
        $users = json_decode(file_get_contents($file_users), true) ?: [];
    }

    // Cek apakah username sudah dipakai
    $username_ada = false;
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $username_ada = true;
            break;
        }
    }

    if ($username_ada) {
        $error = 'Username sudah digunakan, silakan pilih username lain!';
    } else {
        // Tambah akun siswa baru
        $users[] = [
            'username' => $username,
            'password' => $password,
            'nama'     => $nama,
            'role'     => 'siswa'
        ];

        file_put_contents($file_users, json_encode($users, JSON_PRETTY_PRINT));
        $sukses = 'Pendaftaran berhasil! Silakan login.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Siswa - Penjadwalan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow border-0 p-4" style="max-width: 420px; width: 100%; border-radius: 12px;">
        <h3 class="fw-bold text-center text-success mb-2">📝 Registrasi Siswa</h3>
        <p class="text-muted text-center small mb-4">Buat akun untuk melakukan peminjaman fasilitas</p>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small mb-3"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($sukses): ?>
            <div class="alert alert-success py-2 small mb-3">
                <?= htmlspecialchars($sukses); ?> <a href="login.php" class="fw-bold">Login di sini</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Lengkap & Kelas</label>
                <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi (Kelas 8A)" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm mb-3">Daftar Sekarang</button>
        </form>

        <div class="text-center border-top pt-3">
            <span class="small text-muted">Sudah punya akun? </span>
            <a href="login.php" class="small fw-bold text-success">Login di sini</a>
        </div>
    </div>

</body>
</html>
