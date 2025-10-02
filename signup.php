<?php
// signup.php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $pin = password_hash($_POST['pin'], PASSWORD_BCRYPT);
    $two_factor_secret = bin2hex(random_bytes(16));

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, pin, two_factor_secret) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $phone, $password, $pin, $two_factor_secret]);
        $user_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO wallets (user_id, balance) VALUES (?, 1000.00)");
        $stmt->execute([$user_id]);
        $pdo->commit();
        echo "<script>alert('Signup successful! Please login.'); window.location.href = 'login.php';</script>";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - JazzCash Clone</title>
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
        <h2>Sign Up</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="pin" placeholder="4-Digit PIN" pattern="\d{4}" title="PIN must be 4 digits" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="javascript:redirect('login.php')">Login</a></p>
    </div>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
