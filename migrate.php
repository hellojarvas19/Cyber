<?php
// One-time database setup endpoint
// Delete this file after running once

require_once __DIR__ . '/vendor/autoload.php';

// Try to load .env if it exists
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Check both $_ENV and getenv() for Railway compatibility
$dsn  = $_ENV['DB_DSN'] ?? getenv('DB_DSN') ?: '';
$user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: '';
$pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';

if (empty($dsn)) {
    http_response_code(500);
    echo "❌ DB_DSN environment variable not set.\n";
    die();
}

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    // Always run the schema to ensure all tables exist
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schema);
    
    // Check what tables exist
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "✅ Database schema updated successfully!\n\n";
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "❌ Error: " . $e->getMessage();
}
