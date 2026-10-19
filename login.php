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
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['user'] = $user;
            $login_sukses = true;
            
            if ($user['role'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: index.php');
            }
            exit;
        }
    }

    if (!$login_sukses) {
        $error = 'Username atau Password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System - Penjadwalan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow border-0 p-4" style="max-width: 400px; width: 100%; border-radius: 12px;">
        <h3 class="fw-bold text-center text-success mb-3">🔑 Login System</h3>
        <p class="text-muted text-center small mb-4">Masuk sebagai Siswa atau Admin</p>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small mb-3"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">Masuk</button>
        </form>
    </div>

</body>
</html>
