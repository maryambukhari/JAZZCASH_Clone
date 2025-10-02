<?php
// dashboard.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT balance FROM wallets WHERE user_id = ?");
$stmt->execute([$user_id]);
$wallet = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - JazzCash Clone</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff5e62, #f9a825);
            color: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        .balance {
            background: #fff;
            color: #333;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }
        .nav-buttons {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        button {
            background: #ff5e62;
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1.1em;
            margin: 10px;
            transition: background 0.3s;
        }
        button:hover {
            background: #f9a825;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard</h1>
        <div class="balance">
            <h2>Wallet Balance: Rs. <?php echo number_format($wallet['balance'], 2); ?></h2>
        </div>
        <div class="nav-buttons">
            <button onclick="redirect('transfer.php')">Money Transfer</button>
            <button onclick="redirect('bill_payment.php')">Bill Payment</button>
            <button onclick="redirect('transaction_history.php')">Transaction History</button>
            <button onclick="redirect('account.php')">Account Settings</button>
            <button onclick="redirect('index.php')">Logout</button>
        </div>
    </div>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
