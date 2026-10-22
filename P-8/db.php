<?php
/**
 * Practical 8: Database Connection Module using PHP PDO
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT FTE
 */

$host = '127.0.0.1';
$port = '3306';
$dbname = 'studenthub_db';
$username = 'root';
$password = ''; // Default XAMPP password is empty

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
];

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
    // Graceful error logging without exposing sensitive stack traces
    $db_error = $e->getMessage();
    $pdo = null;
}
?>
