<?php

global $conn;
try {

    $dbServer = getenv('DB_SERVER');
    $dbUsername = getenv('DB_USERNAME');
    $dbPassword = getenv('DB_PASSWORD');
    $dbName = getenv('DB_NAME');

    $conn = new PDO("sqlsrv:server=" . $dbServer . ";Database=" . $dbName, $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "Servername:" . $dbServer;
    echo "Username:" . $dbUsername;
    echo "Password:" . $dbPassword;
    echo "DBName:" . $dbName;
    
    die();
}
