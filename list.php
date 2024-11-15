<?php

include "connect.php";

$DB = new DB("pgsql:host=postgres-container;dbname=postgres", "admin", "root");

$pdo = $DB->Conn();
class File {
public $id;
public $name;

public $date;

public $size;
public $path;
}

$stmt = $pdo->query('SELECT id, name, date, size, path FROM files LIMIT 100');

$files = $stmt->fetchAll(PDO::FETCH_CLASS, 'File');

// Вывод в HTML-таблицу
echo "<table border='1' style='border-collapse: collapse; width: 100%; text-align: left;'>";
echo "<tr><th>Имя файла</th><th>Дата загрузки</th><th>Размер (МБ)</th><th>Просмотр</th></tr>";

foreach ($files as $file) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($file->name) . "</td>";
    echo "<td>" . htmlspecialchars($file->date) . "</td>";
    echo "<td>" . htmlspecialchars($file->size) . " МБ</td>";
    // Преобразуем абсолютный путь в относительный URL
    //$filePath = str_replace('/var/www/html', '', $file->path);

    echo "<td><a href='file.php?id=" . htmlspecialchars($file->id) . "'>Открыть</a></td>";

    echo "</tr>";
}

echo "</table>";