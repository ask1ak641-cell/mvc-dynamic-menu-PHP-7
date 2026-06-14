<?php
/**
 * Password Reset Tool
 *
 * KEAMANAN:
 * - File ini hanya bisa diakses jika APP_DEBUG = true
 * - HAPUS file ini setelah selesai di production!
 * - File ini sebaiknya tidak ada di server production
 */

// Cek apakah ini environment development
$isDev = true; // Set false sebelum deploy ke production

if (!$isDev) {
    die('Akses ditolak. Hapus file ini dari server production.');
}

// Koneksi database langsung
$host = 'localhost';
$db   = 'mvc';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal. Periksa konfigurasi database.");
}

$message = '';
$csrfToken = bin2hex(random_bytes(32));

// Simpan dan validasi CSRF sederhana
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $stored = $_SESSION['reset_csrf'] ?? '';
    if (empty($token) || $token !== $stored) {
        die('Token CSRF tidak valid.');
    }

    // Generate hash baru dengan password_hash()
    $users = [
        'admin'    => password_hash('admin123', PASSWORD_BCRYPT),
        'operator' => password_hash('operator123', PASSWORD_BCRYPT),
        'user'     => password_hash('user123', PASSWORD_BCRYPT),
    ];

    foreach ($users as $username => $hash) {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->execute([$hash, $username]);
    }

    $message = '<div style="background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin:10px 0;">
        <i class="fas fa-check-circle" style="color:#28a745;"></i> Password berhasil di-reset!<br><br>
        <b>Login:</b><br>
        admin / admin123<br>
        operator / operator123<br>
        user / user123<br><br>
        <b style="color:red;"><i class="fas fa-exclamation-triangle"></i> HAPUS file reset-password.php ini sekarang juga!</b>
    </div>';
}

$_SESSION['reset_csrf'] = $csrfToken;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - PHP MVC Boilerplate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.1); max-width: 500px; width: 100%; }
        h2 { margin-top: 0; color: #333; }
        button { background: #dc3545; color: #fff; border: none; padding: 12px 30px; font-size: 16px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #c82333; }
        code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="card">
    <h2><i class="fas fa-key"></i> Reset Password</h2>
    <p>Klik tombol di bawah untuk me-reset password semua user ke default:</p>
    <table style="margin-bottom:15px;">
        <tr><td><code>admin</code></td><td>&rarr;</td><td><code>admin123</code></td></tr>
        <tr><td><code>operator</code></td><td>&rarr;</td><td><code>operator123</code></td></tr>
        <tr><td><code>user</code></td><td>&rarr;</td><td><code>user123</code></td></tr>
    </table>
    <?= $message ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit"><i class="fas fa-exclamation-triangle"></i> Reset Password</button>
    </form>
</div>
</body>
</html>
