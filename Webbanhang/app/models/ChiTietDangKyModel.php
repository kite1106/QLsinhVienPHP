<?php

class ChiTietDangKyModel
{
    private $conn;
    private $table_name = "ChiTietDangKy";

    public $MaDK;
    public $MaHP;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getByMaDK($maDK)
    {
        $query = "SELECT c.*, h.TenHP, h.SoTinChi 
                 FROM " . $this->table_name . " c
                 LEFT JOIN HocPhan h ON c.MaHP = h.MaHP 
                 WHERE c.MaDK = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maDK]);
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                SET MaDK=:MaDK, 
                    MaHP=:MaHP";

        $stmt = $this->conn->prepare($query);

        $this->MaDK = htmlspecialchars(strip_tags($this->MaDK));
        $this->MaHP = htmlspecialchars(strip_tags($this->MaHP));

        $stmt->bindParam(":MaDK", $this->MaDK);
        $stmt->bindParam(":MaHP", $this->MaHP);

        return $stmt->execute();
    }

    public function delete($maDK, $maHP)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaDK = ? AND MaHP = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$maDK, $maHP]);
    }

    public function getTotalTinChi($maDK)
    {
        $query = "SELECT SUM(h.SoTinChi) as TongTinChi 
                 FROM " . $this->table_name . " c
                 LEFT JOIN HocPhan h ON c.MaHP = h.MaHP 
                 WHERE c.MaDK = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maDK]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['TongTinChi'] ?? 0;
    }
}
