<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Db.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $pdo = App\Db::pdo();
    echo "✓ Connected to database\n\n";
    
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Total tables: " . count($tables) . "\n";
    
    if (empty($tables)) {
        echo "No tables found in database.\n";
        exit;
    }
    
    foreach ($tables as $table) {
        echo "\n=== Table: $table ===\n";
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        echo "Rows: " . count($rows) . "\n";
        if (!empty($rows)) {
            print_r($rows);
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
