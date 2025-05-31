<?php
// db.php - Recommended and most robust version
$host = 'localhost';
$db   = 'quiz_db';      // CHANGE THIS to your actual database name
$user = 'root';        // CHANGE THIS to your actual DB username
$pass = '';            // CHANGE THIS to your actual DB password (empty for XAMPP root by default)
$port = 3307;          // Explicitly define the port if it's not the default 3306
$charset = 'utf8mb4';

// Incorporate port directly into the DSN string
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Fetch results as associative arrays by default
    PDO::ATTR_EMULATE_PREPARES   => false,                    // Use native prepared statements for security/performance
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>