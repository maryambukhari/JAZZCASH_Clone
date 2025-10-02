<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JazzCash Clone - Home</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ff5e62, #f9a825);
            color: #fff;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        h1 {
            font-size: 3em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        .services {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        .service-card {
            background: #fff;
            color: #333;
            border-radius: 15px;
            padding: 20px;
            width: 200px;
            margin: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }
        .service-card:hover {
            transform: scale(1.05);
        }
        button {
            background: #ff5e62;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
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
        <h1>Welcome to JazzCash Clone</h1>
        <p>Your one-stop solution for digital payments, transfers, and more!</p>
        <div class="services">
            <div class="service-card">
                <h3>Money Transfer</h3>
                <p>Send money instantly to anyone.</p>
            </div>
            <div class="service-card">
                <h3>Bill Payments</h3>
                <p>Pay utility bills with ease.</p>
            </div>
            <div class="service-card">
                <h3>Mobile Recharge</h3>
                <p>Top-up your mobile instantly.</p>
            </div>
        </div>
        <button onclick="redirect('signup.php')">Sign Up</button>
        <button onclick="redirect('login.php')">Login</button>
    </div>
    <script>
        function redirect(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
