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

    $username_ada = false;
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $username_ada = true;
            break;
        }
    }

    if ($username_ada) {
        $error = 'Username sudah digunakan, pilih username lain!';
    } else {
        $users[] = [
            'username' => $username,
            'password' => $password,
            'nama'     => $nama,
            'role'     => 'siswa'
        ];

        file_put_contents($file_users, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $sukses = 'Pendaftaran akun siswa berhasil!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow-lg border-0 p-4" style="max-width: 440px; width: 100%; border-radius: 16px;">
        <div class="text-center mb-3">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold">📝 REGISTRASI SISWA</span>
            <h3 class="fw-bold text-primary mt-2">Buat Akun Siswa Baru</h3>
            <p class="text-muted small">Lengkapi data di bawah untuk melakukan peminjaman</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small mb-3 text-center"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($sukses): ?>
            <div class="alert alert-success py-2 small mb-3 text-center">
                <?= htmlspecialchars($sukses); ?><br>
                <a href="login.php" class="fw-bold text-success text-decoration-none">Klik di sini untuk Login Siswa</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Lengkap & Kelas</label>
                <input type="text" name="nama" class="form-control" placeholder="Contoh: Ahmad (Kelas 9A)" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Buat username" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Buat password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm mb-3" style="border-radius: 8px;">
                Daftar Akun Siswa ✨
            </button>
        </form>

        <div class="text-center border-top pt-3">
            <span class="small text-muted">Sudah punya akun? </span>
            <a href="login.php" class="small fw-bold text-primary text-decoration-none">Login Siswa</a>
        </div>
    </div>

</body>
</html>
