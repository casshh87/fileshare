<?php

include "connect.php";

require_once 'vendor/autoload.php'; // Подключаем getID3

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
    echo "<img src='" . htmlspecialchars($filePath) . "' alt='Изображение' style='width: 150px; height: 150px; object-fit: cover;'>";

} else {
    // Если файл не является изображением спользуем getID3 для анализа файла
    $getID3 = new getID3();
    $fileInfo = $getID3->analyze($file['path']); // Анализируем файл

    if (isset($fileInfo['error'])) {
        echo "<p><strong>Ошибка анализа файла:</strong> " . implode(', ', $fileInfo['error']) . "</p>";
    } else {
        echo "<h2>Дополнительная информация о файле:</h2>";

        // Выводим данные, если это аудио
        if (isset($fileInfo['audio'])) {
            echo "<p><strong>Аудио формат:</strong> " . htmlspecialchars($fileInfo['audio']['dataformat'] ?? 'Неизвестно') . "</p>";
            echo "<p><strong>Кодек:</strong> " . htmlspecialchars($fileInfo['audio']['codec'] ?? 'Неизвестно') . "</p>";
            echo "<p><strong>Частота:</strong> " . htmlspecialchars($fileInfo['audio']['sample_rate'] ?? 'Неизвестно') . " Гц</p>";
        }

        // Выводим данные, если это видео
        if (isset($fileInfo['video'])) {
            echo "<p><strong>Видео формат:</strong> " . htmlspecialchars($fileInfo['video']['dataformat'] ?? 'Неизвестно') . "</p>";
            echo "<p><strong>Ширина:</strong> " . htmlspecialchars($fileInfo['video']['resolution_x'] ?? 'Неизвестно') . " px</p>";
            echo "<p><strong>Высота:</strong> " . htmlspecialchars($fileInfo['video']['resolution_y'] ?? 'Неизвестно') . " px</p>";
        }

        // Общая информация
        echo "<p><strong>Длительность:</strong> " . gmdate("H:i:s", $fileInfo['playtime_seconds'] ?? 0) . "</p>";
    }
}

// Ссылка для скачивания файла
echo "<p><a href='" . htmlspecialchars($filePath) . "' download>Скачать файл</a></p>";

?>
