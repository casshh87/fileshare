<?php
class DB
{
    private $db;
    // Конструктор принимает строку DSN, имя пользователя и пароль
    function __construct($dsn, $username, $password)
    {
        try {
            // Создаем соединение с базой данных
            $this->db = new PDO($dsn, $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Выводим сообщение об ошибке, если соединение не удалось
            echo "Ошибка подключения к базе данных: " . $e->getMessage();
        }
    }
    public function Conn()
    {
        return $this->db;
    }
}

/*
$DB = new DB("pgsql:host=postgres-container;dbname=postgres", "admin", "root");
$conn = $DB->Conn();

if ($conn) {
    echo "Подключение успешно!";
} else {
    echo "Ошибка подключения к базе данных.";
}
*/

?>