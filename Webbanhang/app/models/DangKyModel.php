<?php

class DangKyModel
{
    private $conn;
    private $table_name = "DangKy";
    private $detail_table = "ChiTietDangKy";

    public $MaDK;
    public $NgayDK;
    public $MaSV;
    public $TrangThai;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT d.*, s.HoTen 
                 FROM " . $this->table_name . " d
                 LEFT JOIN SinhVien s ON d.MaSV = s.MaSV";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                SET NgayDK=:NgayDK, 
                    MaSV=:MaSV";

        $stmt = $this->conn->prepare($query);

        $this->NgayDK = htmlspecialchars(strip_tags($this->NgayDK));
        $this->MaSV = htmlspecialchars(strip_tags($this->MaSV));

        $stmt->bindParam(":NgayDK", $this->NgayDK);
        $stmt->bindParam(":MaSV", $this->MaSV);

        if($stmt->execute()){
            $this->MaDK = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function getMaDKByMaSVAndMaHP($maSV, $maHP) {
        $query = "SELECT d.MaDK 
                FROM " . $this->table_name . " d
                JOIN ChiTietDangKy ctdk ON d.MaDK = ctdk.MaDK 
                WHERE d.MaSV = ? AND ctdk.MaHP = ? 
                AND d.TrangThai != 'cancelled'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maSV, $maHP]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['MaDK'] : false;
    }

    public function updateTrangThai($maDK, $trangThai) {
        $query = "UPDATE " . $this->table_name . " 
                SET TrangThai = ? 
                WHERE MaDK = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$trangThai, $maDK]);
    }

    public function getById($id)
    {
        $query = "SELECT d.*, s.HoTen 
                 FROM " . $this->table_name . " d
                 LEFT JOIN SinhVien s ON d.MaSV = s.MaSV 
                 WHERE d.MaDK = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByMaSV($maSV)
    {
        $query = "SELECT d.*, s.HoTen 
                 FROM " . $this->table_name . " d
                 LEFT JOIN SinhVien s ON d.MaSV = s.MaSV 
                 WHERE d.MaSV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maSV]);
        return $stmt;
    }

    public function delete($id)
    {
        // First delete related records in ChiTietDangKy
        $query = "DELETE FROM ChiTietDangKy WHERE MaDK = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        // Then delete the DangKy record
        $query = "DELETE FROM " . $this->table_name . " WHERE MaDK = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }    public function getLatestRegistrations($limit = 5) {
        $query = "SELECT DISTINCT d.*, sv.HoTen, GROUP_CONCAT(hp.TenHP) as DanhSachHP 
                 FROM " . $this->table_name . " d
                 JOIN SinhVien sv ON d.MaSV = sv.MaSV
                 JOIN " . $this->detail_table . " ct ON d.MaDK = ct.MaDK
                 JOIN HocPhan hp ON ct.MaHP = hp.MaHP
                 GROUP BY d.MaDK
                 ORDER BY d.NgayDK DESC 
                 LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    public function getAllWithDetails() {
        $query = "SELECT DISTINCT d.*, sv.HoTen, GROUP_CONCAT(hp.TenHP) as DanhSachHP,
                        GROUP_CONCAT(hp.MaHP) as DanhSachMaHP
                 FROM " . $this->table_name . " d
                 JOIN SinhVien sv ON d.MaSV = sv.MaSV
                 JOIN " . $this->detail_table . " ct ON d.MaDK = ct.MaDK
                 JOIN HocPhan hp ON ct.MaHP = hp.MaHP
                 GROUP BY d.MaDK
                 ORDER BY d.NgayDK DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }    public function approve($id) {
        // Start transaction
        $this->conn->beginTransaction();
        try {
            // Update registration status
            $query = "UPDATE " . $this->table_name . " 
                     SET TrangThai = 'Đã duyệt' 
                     WHERE MaDK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            // Update course enrollment count
            $query = "UPDATE HocPhan hp 
                     JOIN " . $this->detail_table . " ct ON hp.MaHP = ct.MaHP 
                     SET hp.SoLuongDaDangKy = hp.SoLuongDaDangKy + 1 
                     WHERE ct.MaDK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function cancel($id) {
        // Start transaction
        $this->conn->beginTransaction();
        try {
            // First check if registration was approved
            $query = "SELECT TrangThai FROM " . $this->table_name . " WHERE MaDK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Update registration status
            $query = "UPDATE " . $this->table_name . " 
                     SET TrangThai = 'Đã hủy' 
                     WHERE MaDK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            // If it was approved, decrease course enrollment count
            if($row['TrangThai'] == 'Đã duyệt') {
                $query = "UPDATE HocPhan hp 
                         JOIN " . $this->detail_table . " ct ON hp.MaHP = ct.MaHP 
                         SET hp.SoLuongDaDangKy = hp.SoLuongDaDangKy - 1 
                         WHERE ct.MaDK = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$id]);
            }

            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getThongKeTheoNganh() {
        try {
            $query = "SELECT n.MaNganh, n.TenNganh, COUNT(dk.MaDK) as SoLuongDangKy
                     FROM NganhHoc n
                     LEFT JOIN SinhVien sv ON n.MaNganh = sv.MaNganh
                     LEFT JOIN DangKy dk ON sv.MaSV = dk.MaSV
                     WHERE dk.TrangThai = 'Đã duyệt'
                     GROUP BY n.MaNganh, n.TenNganh
                     ORDER BY TenNganh";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in DangKyModel::getThongKeTheoNganh(): " . $e->getMessage());
            return [];
        }
    }

    public function getThongKeTheoHocPhan() {
        try {
            $query = "SELECT h.MaHP, h.TenHP, h.SoLuongDuKien,
                     COUNT(DISTINCT d.MaSV) as SoLuongDangKy
                     FROM HocPhan h
                     LEFT JOIN ChiTietDangKy ct ON h.MaHP = ct.MaHP
                     LEFT JOIN DangKy d ON ct.MaDK = d.MaDK AND d.TrangThai = 'Đã duyệt'
                     GROUP BY h.MaHP, h.TenHP, h.SoLuongDuKien
                     ORDER BY h.TenHP";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in DangKyModel::getThongKeTheoHocPhan(): " . $e->getMessage());
            return [];
        }
    }

    public function dangKyHocPhan($maSV, $maHP) {
        try {
            $this->conn->beginTransaction();

            // Check if student has already registered for this course
            $query = "SELECT d.MaDK, d.TrangThai 
                     FROM DangKy d 
                     JOIN ChiTietDangKy ct ON d.MaDK = ct.MaDK 
                     WHERE d.MaSV = ? AND ct.MaHP = ? 
                     AND d.TrangThai != 'Đã hủy'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$maSV, $maHP]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                throw new Exception("Bạn đã đăng ký học phần này rồi!");
            }

            // Create new registration
            $query = "INSERT INTO DangKy (NgayDK, MaSV, TrangThai) 
                     VALUES (NOW(), ?, 'Chờ duyệt')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$maSV]);
            $maDK = $this->conn->lastInsertId();

            // Add course to registration details
            $query = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$maDK, $maHP]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $e;
        }
    }

    public function huyDangKy($maSV, $maHP)
    {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table_name . " 
                     SET TrangThai = 'Đã hủy'
                     WHERE MaSV = ? AND MaHP = ? AND TrangThai = 'Chờ duyệt'";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$maSV, $maHP]);

            if ($result) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $e;
        }
    }
}

