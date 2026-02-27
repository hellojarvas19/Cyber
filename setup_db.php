<?php
require_once __DIR__ . '/app/Bootstrap.php';
require_once __DIR__ . '/app/Db.php';

try {
    $pdo = App\Db::pdo();
    echo "Connected to database\n";
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    echo "Schema file loaded\n";
    $pdo->exec($schema);
    echo "✅ Database schema created successfully!\n";
    
    // Verify tables
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
    echo "\nTables created:\n";
    while($row = $stmt->fetch()) {
        echo "  - " . $row['tablename'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
