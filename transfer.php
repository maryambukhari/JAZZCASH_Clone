<?php
// transfer.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = $_POST['recipient'];
    $amount = floatval($_POST['amount']);
    $pin = $_POST['pin'];

    try {
        $stmt = $pdo->prepare("SELECT u.pin, w.balance FROM users u JOIN wallets w ON u.id = w.user_id WHERE u.id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (!$user) {
            echo "<script>alert('User or wallet not found.');</script>";
        } elseif (!password_verify($pin, $user['pin'])) {
            echo "<script>alert('Invalid PIN.');</script>";
        } elseif ($user['balance'] < $amount || $amount <= 0) {
            echo "<script>alert('Insufficient balance or invalid amount.');</script>";
        } else {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("UPDATE wallets SET balance = balance - ? WHERE user_id = ?");
            $stmt->execute([$amount, $user_id]);
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, recipient, status) VALUES (?, 'transfer_sent', ?, ?, 'completed')");
            $stmt->execute([$user_id, $amount, $recipient]);
            $pdo->commit();
            echo "<script>alert('Transfer successful!'); window.location.href = 'dashboard.php';</script>";
        }
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
    <title>Money Transfer - JazzCash Clone</title>
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
        <h2>Money Transfer</h2>
        <form method="POST">
            <input type="text" name="recipient" placeholder="Recipient Phone or IBAN" required>
            <input type="number" name="amount" placeholder="Amount" min="1" required>
            <input type="text" name="pin" placeholder="4-Digit PIN" pattern="\d{4}" title="PIN must be 4 digits" required>
            <button type="submit">Transfer</button>
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
