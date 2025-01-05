<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expiry > ?");
    $stmt->execute([$token, date('Y-m-d H:i:s')]);

    if ($stmt->rowCount() > 0) {
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);
        $email = $reset['email'];

        $pdo->prepare("UPDATE users SET password = ? WHERE email = ?")
            ->execute([$new_password, $email]);

        $pdo->prepare("DELETE FROM password_resets WHERE token = ?")
            ->execute([$token]);

        echo "Password berhasil direset.";
    } else {
        echo "Token tidak valid atau sudah kedaluwarsa.";
    }
} else {
    $token = $_GET['token'];
}
?>

<form action="reset_password.php" method="POST">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <input type="password" name="password" placeholder="Password Baru" required>
    <button type="submit">Reset Password</button>
</form>
