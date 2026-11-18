
<?php
require_once __DIR__ . '/config/database.php';

$stmt = $pdo->prepare(
    'SELECT password_hash FROM users WHERE username = ? LIMIT 1'
);
$stmt->execute(['student01']);
$user = $stmt->fetch();

if (!$user) {
    exit('Student account not found.');
}

if (password_verify('Student123!', $user['password_hash'])) {
    echo 'Password matches!';
} else {
    echo 'Password does not match.';
}
