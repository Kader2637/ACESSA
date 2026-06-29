<?php

// Helper script to automatically create PostgreSQL database based on .env configuration

function getEnvValue($key, $default = '') {
    static $env = null;
    if ($env === null) {
        $env = [];
        if (file_exists('.env')) {
            $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2) + [NULL, NULL];
                if ($name !== NULL) {
                    $env[trim($name)] = trim($value, ' "\'');
                }
            }
        }
    }
    return isset($env[$key]) ? $env[$key] : $default;
}

$host = getEnvValue('DB_HOST', '127.0.0.1');
$port = getEnvValue('DB_PORT', '5432');
$username = getEnvValue('DB_USERNAME', 'postgres');
$password = getEnvValue('DB_PASSWORD', '');
$database = getEnvValue('DB_DATABASE', 'Accessa');

echo "Menghubungkan ke PostgreSQL di $host:$port...\n";

try {
    // Connect to system default database 'postgres' to run administrative commands
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=postgres", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists
    $stmt = $pdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
    $stmt->execute([$database]);
    
    if ($stmt->fetchColumn()) {
        echo "Database '$database' sudah ada.\n";
    } else {
        echo "Membuat database '$database'...\n";
        $pdo->exec("CREATE DATABASE \"$database\"");
        echo "Database '$database' berhasil dibuat!\n";
    }
} catch (PDOException $e) {
    echo "Gagal: " . $e->getMessage() . "\n";
    echo "\nPastikan service PostgreSQL Anda sudah menyala, dan username/password di file .env sudah benar.\n";
    exit(1);
}
