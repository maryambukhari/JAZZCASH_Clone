<?php
// transaction_history.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - JazzCash Clone</title>
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
        }
        table {
            width: 100%;
            background: #fff;
            color: #333;
            border-collapse: collapse;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #ff5e62;
            color: #fff;
        }
        tr:hover {
            background: #f1f1f1;
        }
        button {
            background: #ff5e62;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 20px;
            transition: background 0.3s;
        }
        button:hover {
            background: #f9a825;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transaction History</h1>
        <table>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Recipient</th>
                <th>Status</th>
            </tr>
            <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?php echo $transaction['created_at']; ?></td>
                <td><?php echo ucfirst(str_replace('_', ' ', $transaction['type'])); ?></td>
                <td>Rs. <?php echo number_format($transaction['amount'], 2); ?></td>
                <td><?php echo $transaction['recipient'] ?: 'N/A'; ?></td>
                <td><?php echo ucfirst($transaction['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <button onclick="redirect('dashboard.php')">Back to Dashboard</button>
    </div>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
