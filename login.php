<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Cek apakah akun terkunci
        if ($user['lock_until'] && strtotime($user['lock_until']) > time()) {
            echo "Akun terkunci. Coba lagi nanti.";
            exit;
        }

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            echo "Login berhasil!";
            // Reset percobaan gagal
            $pdo->prepare("UPDATE users SET failed_attempts = 0, lock_until = NULL WHERE email = ?")
                ->execute([$email]);
        } else {
            // Tambah percobaan gagal
            $failed_attempts = $user['failed_attempts'] + 1;
            $lock_until = $failed_attempts >= 5 ? date('Y-m-d H:i:s', strtotime('+15 minutes')) : NULL;

            $pdo->prepare("UPDATE users SET failed_attempts = ?, lock_until = ? WHERE email = ?")
                ->execute([$failed_attempts, $lock_until, $email]);

            echo "Password salah.";
        }
    } else {
        echo "Email tidak terdaftar.";
    }
}
?>

<form action="login.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
