
<?php
session_start();

require_once __DIR__ . '/config/database.php';

// Run this setup script only while testing locally.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Generate Student Demo Passwords</title>
    </head>
    <body>
        <h2>Generate Student Demo Passwords</h2>
        <p>This generates passwords for the 12 fictional research accounts.</p>
        <form method="POST">
            <button type="submit">Generate Passwords</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->query(
        "SELECT Student_ID, Full_Name
         FROM students
         WHERE Student_ID IN (
             '2026-1101', '2026-1102', '2026-1103',
             '2026-1106', '2026-1107', '2026-1108',
             '2025-1201', '2025-1202', '2025-1203',
             '2025-1206', '2025-1207', '2025-1208'
         )
         ORDER BY Student_ID"
    );

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($students) !== 12) {
        throw new RuntimeException(
            'Expected 12 fictional students, but found ' . count($students) . '.'
        );
    }

    $update = $pdo->prepare(
        "UPDATE students
         SET Password_Hash = :password_hash
         WHERE Student_ID = :student_id"
    );

    $credentials = [];

    foreach ($students as $student) {
        // Generate a unique, random demo password.
        $password = bin2hex(random_bytes(6));

        $update->execute([
            'password_hash' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            'student_id' => $student['Student_ID']
        ]);

        $credentials[] = [
            'student_number' => $student['Student_ID'],
            'full_name' => $student['Full_Name'],
            'password' => $password
        ];
    }

    $pdo->commit();

    // Display credentials once; the original passwords aren't stored.
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Student Demo Credentials</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 30px;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #ccc;
                padding: 10px;
                text-align: left;
            }

            th {
                background: #f2f2f2;
            }

            button {
                padding: 9px 14px;
                cursor: pointer;
            }

            @media print {
                button { display: none; }
            }
        </style>
    </head>
    <body>
        <h2>Student Demo Credentials</h2>
        <p>Save or print this list now. The passwords cannot be recovered
           from the stored hashes.</p>

        <button onclick="window.print()">Print / Save as PDF</button>

        <table>
            <thead>
                <tr>
                    <th>Student Number / Login ID</th>
                    <th>Student Name</th>
                    <th>Demo Password</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($credentials as $credential): ?>
                    <tr>
                        <td><?= htmlspecialchars($credential['student_number']) ?></td>
                        <td><?= htmlspecialchars($credential['full_name']) ?></td>
                        <td><?= htmlspecialchars($credential['password']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo 'Setup failed: ' . htmlspecialchars($e->getMessage());
}
