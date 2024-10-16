<?php

// Определение пути к файлу SQLite
define('PATH_TO_SQLITE_FILE', 'c:/Users/user/project/db.db');

// Настройка опций для PDO
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Создание объекта PDO
    $db = new PDO('sqlite:' . PATH_TO_SQLITE_FILE, null, null, $opt);

    echo "Подключение к базе данных успешно!";
} catch (PDOException $e) {
    echo 'Ошибка подключения к базе данных: ' . $e->getMessage();
    exit();
}

?>
