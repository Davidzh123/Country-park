<?php
function getDatabaseConnection() {
    $dsn = "mysql:host=localhost;dbname=country park";
    $user = "root";
    $password = "";
    $pdoOptions = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
    $databaseConnection = new PDO($dsn , $user , $password, $pdoOptions);
    return $databaseConnection;
}