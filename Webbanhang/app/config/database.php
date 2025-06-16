<?php

class Database
{

    private $host = 'localhost';
    private $db_name = 'test1';
    private $user_name = 'root';
    private $password = '';
    public $db;

    public function getConnection()
    {
        $this->db = null;

        try {
            $this->db = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->user_name, $this->password);
            $this->db->exec('set names utf8');
        } catch (PDOException $ex) {
            echo "Không kết nối thàn công lỗi: " . $ex->getMessage();
        }

        return $this->db;
    }
}
