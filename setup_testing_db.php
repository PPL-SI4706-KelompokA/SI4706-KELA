<?php
$host = '127.0.0.1';
$port = '3306';
$username = 'root';
$password = '';
$database = 'foodshare_testing';

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    echo "Creating database if not exists: $database...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

    // Connect to the specific database
    $pdo->exec("USE `$database`;");
    echo "Database selected: $database\n";

    // Read SQL file
    $sqlFile = __DIR__ . '/database_testing.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("File database_testing.sql not found at $sqlFile");
    }

    echo "Reading database_testing.sql...\n";
    $sql = file_get_contents($sqlFile);

    // Execute the SQL file content
    echo "Executing SQL statements...\n";
    $pdo->exec($sql);

    echo "Testing database setup completed successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
