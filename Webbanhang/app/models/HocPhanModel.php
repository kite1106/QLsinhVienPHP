<?php

class HocPhanModel
{
    private $conn;
    private $table_name = "HocPhan";

    public $MaHP;
    public $TenHP;
    public $SoTinChi;
    public $SoLuongDuKien;
    public $SoLuongDaDangKy;
    public $MoTa; // Add this line

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        try {
            $sql = "SELECT 
                    h.MaHP, 
                    h.TenHP, 
                    h.SoTinChi, 
                    h.SoLuongDuKien, 
                    h.SoLuongDaDangKy 
                FROM " . $this->table_name . " h";
                
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error in HocPhanModel::getAll(): " . $e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        $query = "SELECT MaHP, TenHP, SoTinChi, SoLuongDuKien, SoLuongDaDangKy 
                 FROM " . $this->table_name . " 
                 WHERE MaHP = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create()
    {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (MaHP, TenHP, SoTinChi, SoLuongDuKien, SoLuongDaDangKy) 
                    VALUES (?, ?, ?, ?, 0)";
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $this->MaHP,
                $this->TenHP,
                $this->SoTinChi,
                $this->SoLuongDuKien ?? 40
            ]);
        } catch (PDOException $e) {
            error_log("Error in HocPhanModel::create(): " . $e->getMessage());
            return false;
        }
    }

    public function getDanhSachHocPhanChoSinhVien($maSV) {
        $query = "SELECT h.MaHP, h.TenHP, h.SoTinChi, 
                        h.SoLuongDuKien, h.SoLuongDaDangKy,
                        (SELECT d.TrangThai 
                         FROM DangKy d 
                         JOIN ChiTietDangKy ct ON d.MaDK = ct.MaDK
                         WHERE d.MaSV = ? AND ct.MaHP = h.MaHP 
                         ORDER BY d.NgayDK DESC LIMIT 1) as TrangThai
                 FROM " . $this->table_name . " h";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maSV]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkSlotAvailable($maHP) {
        $query = "SELECT SoLuongDuKien, SoLuongDaDangKy 
                FROM " . $this->table_name . " 
                WHERE MaHP = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maHP]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return ($result['SoLuongDaDangKy'] < $result['SoLuongDuKien']);
    }

    public function tangSoLuongDaDangKy($maHP) {
        $query = "UPDATE " . $this->table_name . " 
                SET SoLuongDaDangKy = SoLuongDaDangKy + 1 
                WHERE MaHP = ? AND SoLuongDaDangKy < SoLuongDuKien";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$maHP]);
    }

    public function giamSoLuongDaDangKy($maHP) {
        $query = "UPDATE " . $this->table_name . " 
                SET SoLuongDaDangKy = GREATEST(SoLuongDaDangKy - 1, 0)
                WHERE MaHP = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$maHP]);
    }

    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " 
                    SET TenHP = ?, 
                        SoTinChi = ?, 
                        SoLuongDuKien = ?,
                        MoTa = ?
                    WHERE MaHP = ?";
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $this->TenHP,
                $this->SoTinChi,
                $this->SoLuongDuKien,
                $this->MoTa,
                $this->MaHP
            ]);
        } catch (PDOException $e) {
            error_log("Error in HocPhanModel::update(): " . $e->getMessage());
            return false;
        }
    }

    // Lấy danh sách học phần có thể đăng ký cho sinh viên
    public function getAvailableCourses($maSV)
    {
        $query = "SELECT hp.*, 
                    (SELECT COUNT(*) 
                     FROM DangKy dk 
                     JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK
                     WHERE ct.MaHP = hp.MaHP 
                     AND dk.TrangThai = 'Đã duyệt') as SoLuongDaDangKy,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM DangKy dk 
                            JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK
                            WHERE ct.MaHP = hp.MaHP 
                            AND dk.MaSV = ? 
                            AND dk.TrangThai IN ('Đã duyệt', 'Chờ duyệt')
                        ) THEN 1 
                        ELSE 0 
                    END as DaDangKy
                FROM " . $this->table_name . " hp
                WHERE (SELECT COUNT(*) 
                      FROM DangKy dk 
                      JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK
                      WHERE ct.MaHP = hp.MaHP 
                      AND dk.TrangThai = 'Đã duyệt') < hp.SoLuongDuKien
                ORDER BY hp.MaHP";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maSV]);
        return $stmt;
    }

    // Xóa học phần
    public function delete($maHP) {
        try {
            $this->conn->beginTransaction();

            // Xóa từ ChiTietDangKy trước
            $query = "DELETE FROM ChiTietDangKy WHERE MaHP = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$maHP]);

            // Sau đó xóa học phần
            $query = "DELETE FROM " . $this->table_name . " WHERE MaHP = ?";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$maHP]);

            $this->conn->commit();
            return $result;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error in HocPhanModel::delete(): " . $e->getMessage());
            return false;
        }
    }

    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}


