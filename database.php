<?php
    // establishes connection 
    $config = require 'config.php';
    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['db']}";

        $pdo = new PDO($dsn, $config['username'], $config['password']);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) { 
        echo "Connection failed: ". $e->getMessage();
            }
?>