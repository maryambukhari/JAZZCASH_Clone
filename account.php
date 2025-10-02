<?php
// account.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pin = $_POST['current_pin'];
    $new_pin = password_hash($_POST['new_pin'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("SELECT pin FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_pin, $user['pin'])) {
            $stmt = $pdo->prepare("UPDATE users SET pin = ? WHERE id = ?");
            $stmt->execute([$new_pin, $user_id]);
            echo "<script>alert('PIN updated successfully!'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Invalid current PIN.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - JazzCash Clone</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #6b7280, #1f2937);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #ff5e62;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #ff5e62;
            color: #fff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background 0.3s;
        }
        button:hover {
            background: #f9a825;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Account Settings</h2>
        <form method="POST">
            <input type="text" name="current_pin" placeholder="Current PIN" pattern="\d{4}" title="PIN must be 4 digits" required>
            <input type="text" name="new_pin" placeholder="New PIN" pattern="\d{4}" title="PIN must be 4 digits" required>
            <button type="submit">Update PIN</button>
        </form>
        <p><a href="javascript:redirect('dashboard.php')">Back to Dashboard</a></p>
    </div>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
