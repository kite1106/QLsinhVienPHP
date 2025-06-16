<?php

class NganhHocModel
{
    private $conn;
    private $table_name = "NganhHoc";

    public $MaNganh;
    public $TenNganh;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaNganh = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET MaNganh=:MaNganh, TenNganh=:TenNganh";
        $stmt = $this->conn->prepare($query);

        $this->MaNganh = htmlspecialchars(strip_tags($this->MaNganh));
        $this->TenNganh = htmlspecialchars(strip_tags($this->TenNganh));

        $stmt->bindParam(":MaNganh", $this->MaNganh);
        $stmt->bindParam(":TenNganh", $this->TenNganh);

        return $stmt->execute();
    }
}
