<?php
// db.php
$host = 'localhost';
$dbname = 'dbjmkphr3ybap7';
$username = 'uasxxqbztmxwm';
$password = 'wss863wqyhal';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
