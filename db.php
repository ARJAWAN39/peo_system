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

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>