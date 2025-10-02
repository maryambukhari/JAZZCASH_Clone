<?php
// bill_payment.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bill_type = $_POST['bill_type'];
    $bill_reference = $_POST['bill_reference'];
    $amount = $_POST['amount'];
    $pin = $_POST['pin'];

    $stmt = $pdo->prepare("SELECT pin, balance FROM users u JOIN wallets w ON u.id = w.user_id WHERE u.id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (password_verify($pin, $user['pin']) && $user['balance'] >= $amount) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("UPDATE wallets SET balance = balance - ? WHERE user_id = ?");
            $stmt->execute([$amount, $user_id]);
            $stmt = $pdo->prepare("INSERT INTO bills (user_id, bill_type, bill_reference, amount, status) VALUES (?, ?, ?, ?, 'paid')");
            $stmt->execute([$user_id, $bill_type, $bill_reference, $amount]);
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, status) VALUES (?, ?, ?, 'completed')");
            $stmt->execute([$user_id, $bill_type == 'mobile' ? 'mobile_recharge' : 'bill_payment', $amount]);
            $pdo->commit();
            echo "<script>alert('Payment successful!'); window.location.href = 'dashboard.php';</script>";
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Invalid PIN or insufficient balance.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Payment - JazzCash Clone</title>
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
        select, input {
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
        <h2>Bill Payment / Mobile Recharge</h2>
        <form method="POST">
            <select name="bill_type" required>
                <option value="">Select Type</option>
                <option value="electricity">Electricity</option>
                <option value="gas">Gas</option>
                <option value="water">Water</option>
                <option value="internet">Internet</option>
                <option value="mobile">Mobile Recharge</option>
            </select>
            <input type="text" name="bill_reference" placeholder="Bill Reference / Phone Number" required>
            <input type="number" name="amount" placeholder="Amount" required>
            <input type="password" name="pin" placeholder="4-Digit PIN" required>
            <button type="submit">Pay</button>
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
