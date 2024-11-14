<?php

include 'connect.php';

$uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
$name = $_FILES['file']['name'];
$size = $_FILES['file']['size'] / 1048576;
$size = round($size, 2); // Округляем до двух знаков после запятой
$path = $uploadsDir . basename($name); // Полный путь, по которому будет сохранен файл
$type = $_FILES['file']['type'];
$date = date("Y-m-d H:i:s"); // Получаем текущую дату и время
$comment = $_POST['comment'];

class File
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function addFile($name, $size, $path, $type, $date, $comment)
    {
        $sql = "INSERT INTO files (name, size, path, type, date, comment) VALUES (:name, :size, :path, :type, :date, :comment)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindparam(':name', $name);
        $stmt->bindparam(':size', $size);
        $stmt->bindparam(':path', $path);  // Здесь мы будем использовать $targetPath
        $stmt->bindparam(':type', $type);
        $stmt->bindparam(':date', $date);
        $stmt->bindparam(':comment', $comment);

        $stmt->execute();

        move_uploaded_file($_FILES['file']['tmp_name'], $path);
    }
}

$DB = new DB("pgsql:host=postgres-container;dbname=postgres", "admin", "root");
$conn = $DB->Conn();
$file = new File($conn);

$file->addFile($name, $size, $path, $type, $date, $comment);
