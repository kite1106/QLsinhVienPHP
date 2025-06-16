<?php

class SinhVienModel {
    private $conn;
    private $table_name = "SinhVien";

    public $MaSV;
    public $HoTen;
    public $GioiTinh;
    public $NgaySinh;
    public $Hinh;
    public $MaNganh;
    public $MatKhau;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function checkLogin($maSV, $matKhau) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaSV = ? AND MatKhau = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$maSV, $matKhau]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $query = "SELECT sv.*, n.TenNganh 
                  FROM " . $this->table_name . " sv
                  LEFT JOIN NganhHoc n ON sv.MaNganh = n.MaNganh
                  ORDER BY sv.MaSV";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }  

    public function getById($id) {
        $query = "SELECT sv.*, nh.TenNganh 
                 FROM SinhVien sv
                 LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh
                 WHERE sv.MaSV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET MaSV=:MaSV, 
                    HoTen=:HoTen, 
                    GioiTinh=:GioiTinh, 
                    NgaySinh=:NgaySinh, 
                    Hinh=:Hinh, 
                    MaNganh=:MaNganh,
                    MatKhau=:MatKhau";

        $stmt = $this->conn->prepare($query);

        // Sanitize and validate input
        $this->MaSV = $this->sanitizeInput($this->MaSV);
        $this->HoTen = $this->sanitizeInput($this->HoTen);
        $this->GioiTinh = $this->sanitizeInput($this->GioiTinh);
        $this->NgaySinh = $this->sanitizeInput($this->NgaySinh);
        $this->Hinh = $this->sanitizeInput($this->Hinh);
        $this->MaNganh = $this->sanitizeInput($this->MaNganh);
        
        // Validate required fields
        if (empty($this->MaSV) || empty($this->HoTen) || empty($this->MaNganh)) {
            throw new Exception("Mã SV, Họ tên và Mã ngành không được để trống!");
        }

        // Set default password if not provided
        if (empty($this->MatKhau)) {
            $this->MatKhau = '123456';
        }

        // Hash the password
        $hashedPassword = md5($this->MatKhau);

        // Bind values
        $stmt->bindParam(":MaSV", $this->MaSV);
        $stmt->bindParam(":HoTen", $this->HoTen);
        $stmt->bindParam(":GioiTinh", $this->GioiTinh);
        $stmt->bindParam(":NgaySinh", $this->NgaySinh);
        $stmt->bindParam(":Hinh", $this->Hinh);
        $stmt->bindParam(":MaNganh", $this->MaNganh);
        $stmt->bindParam(":MatKhau", $hashedPassword);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Duplicate entry error
                throw new Exception("Mã sinh viên đã tồn tại!");
            }
            throw $e;
        }
    }

    private function sanitizeInput($input) {
        if ($input === null) {
            return '';
        }
        return htmlspecialchars(strip_tags(trim($input)));
    }

    public function update($data) {
        try {
            $updateFields = [];
            $values = [];

            // Build update query dynamically
            foreach ($data as $key => $value) {
                if ($key !== 'MaSV') { // Skip MaSV as it's the WHERE condition
                    if ($key === 'MatKhau') {
                        $value = md5($value); // Hash password if it's being updated
                    }
                    $updateFields[] = "$key = ?";
                    $values[] = $value;
                }
            }

            // Add WHERE condition value
            $values[] = $data['MaSV'];

            // Construct final query
            $query = "UPDATE " . $this->table_name . " SET " . 
                    implode(", ", $updateFields) . 
                    " WHERE MaSV = ?";

            $stmt = $this->conn->prepare($query);
            return $stmt->execute($values);

        } catch (PDOException $e) {
            throw new Exception("Lỗi cập nhật: " . $e->getMessage());
        }
    }

    public function delete($id) {
        // First check if student has any registrations
        $query = "SELECT COUNT(*) FROM DangKy WHERE MaSV = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return false; // Cannot delete student with registrations
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE MaSV = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function changePassword($maSV, $oldPassword, $newPassword) {
        // First verify old password
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE MaSV = ? AND MatKhau = ?";
        $stmt = $this->conn->prepare($query);
        $hashedOldPassword = md5($oldPassword);
        $stmt->execute([$maSV, $hashedOldPassword]);
        
        if ($stmt->fetchColumn() == 0) {
            return false; // Old password is incorrect
        }

        // Update to new password
        $query = "UPDATE " . $this->table_name . " SET MatKhau = ? WHERE MaSV = ?";
        $stmt = $this->conn->prepare($query);
        $hashedNewPassword = md5($newPassword);
        return $stmt->execute([$hashedNewPassword, $maSV]);
    }
}
