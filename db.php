<?php
// Display errors to help us debug if something goes wrong
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_url = getenv('MYSQL_URL');

if ($db_url) {
    // We are on Railway
    $url = parse_url($db_url);
    $host = $url['host'];
    $user = $url['user'];
    $pass = $url['pass'];
    $db   = substr($url['path'], 1);
    $port = $url['port'];
} else {
    // We are on Local Laragon
    $host = 'localhost';
    $user = 'root';
    $pass = ''; 
    $db   = 'peo_system'; 
    $port = '3306';
}

// Replace your old $conn line with this PDO setup:
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    // This tells PHP to show an error if the SQL is wrong
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>