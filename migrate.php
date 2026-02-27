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
    echo "Available env keys: " . implode(', ', array_keys($_ENV)) . "\n";
    echo "getenv DB_DSN: " . (getenv('DB_DSN') ?: 'not set') . "\n";
    die();
}

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    // Check if tables exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'users'");
    $exists = $stmt->fetchColumn() > 0;
    
    if ($exists) {
        echo "✅ Database already set up!";
    } else {
        $schema = file_get_contents(__DIR__ . '/schema.sql');
        $pdo->exec($schema);
        echo "✅ Database schema created successfully!";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "❌ Error: " . $e->getMessage();
}
