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

    // Cek apakah username sudah terpakai
    $username_ada = false;
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $username_ada = true;
            break;
        }
    }

    if ($username_ada) {
        $error = 'Username sudah digunakan, pilih username admin lain!';
    } else {
        // Tambahkan akun admin baru dengan role 'admin'
        $users[] = [
            'username' => $username,
            'password' => $password,
            'nama'     => $nama,
            'role'     => 'admin'
        ];

        file_put_contents($file_users, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $sukses = 'Pendaftaran akun administrator baru berhasil!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Admin - Penjadwalan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow-lg border-secondary text-white bg-secondary p-4" style="max-width: 440px; width: 100%; border-radius: 16px; --bs-bg-opacity: .2;">
        <div class="text-center mb-3">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">⚙️ REGISTRASI ADMIN</span>
            <h3 class="fw-bold text-white mt-2">Tambah Admin Baru</h3>
            <p class="text-light small opacity-75">Form pembuatan akun pengelola/administrator sekolah</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small mb-3 text-center border-0"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($sukses): ?>
            <div class="alert alert-success py-2 small mb-3 text-center border-0">
                <?= htmlspecialchars($sukses); ?><br>
                <a href="login-admin.php" class="fw-bold text-dark text-decoration-underline">Klik di sini untuk Login Admin</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold text-light">Nama Pengelola / Jabatan</label>
                <input type="text" name="nama" class="form-control bg-dark text-white border-secondary" placeholder="Contoh: Pak Budi (Pengelola Sarpras)" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold text-light">Username Admin</label>
                <input type="text" name="username" class="form-control bg-dark text-white border-secondary" placeholder="Buat username admin" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold text-light">Password</label>
                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="Buat password admin" required>
            </div>
            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 shadow-sm mb-3" style="border-radius: 8px;">
                Daftar Akun Admin 🛡️
            </button>
        </form>

        <div class="text-center border-top border-secondary pt-3">
            <a href="login-admin.php" class="small text-light text-decoration-none">⬅ Kembali ke Login Admin</a>
        </div>
    </div>

</body>
</html>
