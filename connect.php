<?php
class DB
{
    private $dsn;
    private $db;
    function __construct($dsn)
    {
        $this->dsn = $dsn;

        try {
            $this->db = new PDO($this->dsn);

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            print $e->getMessage();
        }
    }
    public function Conn()
    {
        return $this->db;
    }
}
