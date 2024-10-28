<?php

$dsn = 'sqlite:C:/Users/user/project/db.db';

try {
    $pdo = new PDO($dsn);
   
    echo "Connected to the SQLite database successfully!";
} catch (PDOException $e) {
    echo $e->getMessage();
}
