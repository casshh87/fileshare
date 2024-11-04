<?php

include 'connect.php';

$name = $_FILES['file']['name'];
$size = $_FILES['file']['size']  / 1048576;
$size = round($size, 2); // округляем до двух знаков после запятой
$path = $_FILES['file']['tmp_name'];
$type = $_FILES['file']['type'];
$date = date("Y-m-d H:i:s"); // Получаем текущую дату и время
$comment = $_POST['comment'];

//echo "Файл: $name, Размер: $size, Тип: $type, Загружен: $date, Комментарий: $comment";

class File
{

    private $db;

    // Конструктор принимает объект подключения
    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }


    // Метод для добавления файла в таблицу
    public function addFile($name, $size, $path, $type, $date, $comment)
    {
        $uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

        if (is_uploaded_file($path)) {

            $sql = "INSERT INTO files (name, size, path, type, date, comment) VALUES (:name, :size, :path, :type, :date, :comment)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindparam(':name', $name);
            $stmt->bindparam(':size', $size);
            $stmt->bindparam(':path', $path);
            $stmt->bindparam(':type', $type);
            $stmt->bindparam(':date', $date);
            $stmt->bindparam(':comment', $comment);

            $stmt->execute();

            move_uploaded_file($path, $uploadsDir . $name);

            header("list.php");
        }
    }
}


$DB = new DB('sqlite:' . __DIR__ . '/db.db');

$conn = $DB->Conn();

$file = new File($conn);

$file->addFile($name, $size, $path, $type, $date, $comment);
