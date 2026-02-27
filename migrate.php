<?php
// One-time database setup endpoint
// Delete this file after running once

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dsn  = $_ENV['DB_DSN'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

if (empty($dsn)) {
    http_response_code(500);
    die("❌ DB_DSN environment variable not set. Please configure environment variables in Railway dashboard.");
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
