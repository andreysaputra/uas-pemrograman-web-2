<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $pdo->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)")
            ->execute([$email, $token, $expiry]);

        echo "Email reset password telah dikirim.";
        // Simulasikan pengiriman email
        echo "Klik link ini untuk reset password: https://example.com/reset_password.php?token=$token";
    } else {
        echo "Email tidak ditemukan.";
    }
}
?>

<form action="forgot_password.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Reset Password</button>
</form>
