
<?php
require_once __DIR__ . '/config/database.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($fullName === '' || $username === '' || $password === '') {
        $message = 'Please fill in all fields.';
    } else {
        try {
            $check = $pdo->prepare(
                "SELECT COUNT(*) FROM users WHERE role = 'admin'"
            );
            $check->execute();

            if ((int)$check->fetchColumn() > 0) {
                $message = 'An admin account already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare(
                    "INSERT INTO users
                    (full_name, username, password_hash, role)
                    VALUES (:full_name, :username, :password_hash, 'admin')"
                );

                $stmt->execute([
                    'full_name' => $fullName,
                    'username' => $username,
                    'password_hash' => $hash
                ]);

                $message = 'Admin account created successfully!';
                $success = true;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $message = 'Could not create the account. Check whether the username already exists.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUEUESKI Admin Setup</title>
</head>
<body>
    <h2>Create First Admin Account</h2>

    <?php if ($message !== ''): ?>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form method="POST">
            <label for="full_name">Full Name</label><br>
            <input
                id="full_name"
                name="full_name"
                required
            ><br><br>

            <label for="username">Admin Username</label><br>
            <input
                id="username"
                name="username"
                required
                autocomplete="username"
            ><br><br>

            <label for="password">Admin Password</label><br>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            ><br><br>

            <button type="submit">Create Admin Account</button>
        </form>
    <?php endif; ?>
</body>
</html>
