<?php
const DB_HOST = 'sql206.infinityfree.com';  
const DB_NAME = 'if0_40508345_prozhektor_db';  
const DB_USER = 'if0_40508345';  
const DB_PASS = 'x30D43heWDJB6xj';  

function get_pdo(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    return $pdo;
}
?>
