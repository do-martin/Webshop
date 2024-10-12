<?php

global $conn;
try {

    $dbServer = getenv('DB_SERVER');
    $dbUsername = getenv('DB_USERNAME');
    $dbPassword = getenv('DB_PASSWORD');
    $dbName = getenv('DB_NAME');

    // $conn = new PDO("sqlsrv:server=" . $dbServer . ";Database=" . $dbName, $dbUsername, $dbPassword);
    $conn = new PDO("mysql:host=" . $dbServer . "; port=3306; dbname=" . $dbName, $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "Servername:" . $dbServer;
    die();
}
