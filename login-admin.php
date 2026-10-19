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
        if ($user['username'] === $username && $user['password'] === $password && $user['role'] === 'admin') {
            $_SESSION['user'] = $user;
            $login_sukses = true;
            header('Location: admin.php');
            exit;
        }
    }

    if (!$login_sukses) {
        $error = 'Akses Ditolak! Username / Password Admin tidak valid.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Admin - Penjadwalan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow-lg border-secondary text-white bg-secondary p-4" style="max-width: 420px; width: 100%; border-radius: 16px; --bs-bg-opacity: .2;">
        <div class="text-center mb-3">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">⚙️ DASHBOARD ADMIN</span>
            <h3 class="fw-bold text-white mt-2">Login Pengelola</h3>
            <p class="text-light small opacity-75">Area terbatas khusus administrator sekolah</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small mb-3 text-center border-0"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold text-light">Username Admin</label>
                <input type="text" name="username" class="form-control bg-dark text-white border-secondary" placeholder="Masukkan username admin" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold text-light">Password</label>
                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="Masukkan password admin" required>
            </div>
            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 shadow-sm mb-3" style="border-radius: 8px;">
                Masuk Dashboard Admin 🔒
            </button>
        </form>

        <div class="text-center border-top border-secondary pt-3 mt-2">
            <a href="login.php" class="small text-light text-decoration-none">⬅ Kembali ke Portal Siswa</a>
        </div>
    </div>

</body>
</html>
