<?php

include "connect.php";

// Проверяем, есть ли переданный параметр `id`
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Неверный идентификатор файла.";
    exit;
}

// Получаем ID файла
$fileId = (int)$_GET['id'];

// Создаем подключение к базе данных
$DB = new DB("pgsql:host=postgres-container;dbname=postgres", "admin", "root");
$pdo = $DB->Conn();

// Получаем информацию о файле по ID
$stmt = $pdo->prepare('SELECT id, name, date, size, path, comment FROM files WHERE id = :id');
$stmt->execute(['id' => $fileId]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

// Если файл не найден
if (!$file) {
    echo "Файл не найден.";
    exit;
}

// Отображаем информацию о файле
echo "<h1>Информация о файле</h1>";
echo "<p><strong>Имя файла:</strong> " . htmlspecialchars($file['name']) . "</p>";
echo "<p><strong>Дата загрузки:</strong> " . htmlspecialchars($file['date']) . "</p>";
echo "<p><strong>Размер:</strong> " . htmlspecialchars($file['size']) . " МБ</p>";
echo "<p><strong>Комментарий автора:</strong> " . htmlspecialchars($file['comment']) . "</p>";

// Преобразуем абсолютный путь в относительный URL
$filePath = str_replace('/var/www/html', '', $file['path']);

// Проверяем, является ли файл изображением
$imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
$fileExtension = strtolower(pathinfo($file['path'], PATHINFO_EXTENSION));

if (in_array($fileExtension, $imageTypes)) {
    // Если файл - изображение, отображаем его
    echo "<p><strong>Изображение:</strong></p>";
    echo "<img src='" . htmlspecialchars($filePath) . "' alt='Изображение' style='max-width: 100%; height: auto;'>";
} else {
    // Если файл не является изображением
    echo "<p>Этот файл не поддерживает отображение как изображение.</p>";
}

// Ссылка для скачивания файла
echo "<p><a href='" . htmlspecialchars($filePath) . "' download>Скачать файл</a></p>";

?>
