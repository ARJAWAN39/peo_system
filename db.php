<?php
// Use environment variables for Railway deployment, fallback to local settings
$host = getenv('MYSQLHOST') ?: "localhost";
$dbname = getenv('MYSQLDATABASE') ?: "peo_system";
$username = getenv('MYSQLUSER') ?: "root";
$password = getenv('MYSQLPASSWORD') ?: "";
$port = getenv('MYSQLPORT') ?: "3306";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($host === "localhost") {
        $pdo->exec("USE `$dbname` ");
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>