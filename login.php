<?php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $file_users = 'users.json';
    $users = [];

    if (file_exists($file_users)) {
        $users = json_decode(file_get_contents($file_users), true) ?: [];
    }

    $login_sukses = false;

    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password && $user['role'] === 'siswa') {
            $_SESSION['user'] = $user;
            $login_sukses = true;
            header('Location: index.php');
            exit;
        }
    }

    if (!$login_sukses) {
        $error = 'Username/Password siswa salah atau akun bukan role siswa!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Siswa - Penjadwalan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow-lg border-0 p-4" style="max-width: 420px; width: 100%; border-radius: 16px;">
        <div class="text-center mb-3">
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">🎓 PORTAL SISWA</span>
            <h3 class="fw-bold text-success mt-2">Login Peminjaman</h3>
            <p class="text-muted small">Silakan masuk menggunakan akun siswa Anda</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small mb-3 text-center"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Username Siswa</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm mb-3" style="border-radius: 8px;">
                Masuk Portal Siswa 🚀
            </button>
        </form>

        <div class="text-center border-top pt-3">
            <span class="small text-muted">Belum punya akun? </span>
            <a href="register.php" class="small fw-bold text-success text-decoration-none">Daftar Akun Siswa</a>
        </div>

        <div class="text-center mt-3 pt-2">
            <a href="login-admin.php" class="small text-secondary text-decoration-none">⚙️ Masuk sebagai Admin / Pengelola</a>
        </div>
    </div>

</body>
</html>
