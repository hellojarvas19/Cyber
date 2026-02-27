<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dsn  = $_ENV['DB_DSN'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

echo "Connecting to: $dsn\n";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Connected!\n\n";
    
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schema);
    echo "✅ Schema executed!\n\n";
    
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
    echo "Tables:\n";
    while($row = $stmt->fetch()) {
        echo "  - " . $row['tablename'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
