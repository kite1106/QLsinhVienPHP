<?php
require_once 'app/models/DangKyModel.php';
require_once 'app/models/ChiTietDangKyModel.php';
require_once 'app/models/SinhVienModel.php';
require_once 'app/models/HocPhanModel.php';
require_once 'app/helpers/SessionHelper.php';

class DangKyController {
    private $dangKyModel;
    private $chiTietDangKyModel;
    private $sinhVienModel;
    private $hocPhanModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->dangKyModel = new DangKyModel($db);
        $this->chiTietDangKyModel = new ChiTietDangKyModel($db);
        $this->sinhVienModel = new SinhVienModel($db);
        $this->hocPhanModel = new HocPhanModel($db);
    }

    public function index() {
        $result = $this->dangKyModel->getAll();
        $dangKy = $result->fetchAll(PDO::FETCH_ASSOC);
        require 'app/views/dangky/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->dangKyModel->NgayDK = date('Y-m-d');
            $this->dangKyModel->MaSV = $_POST['MaSV'];

            if ($this->dangKyModel->create()) {
                $maDK = $this->dangKyModel->MaDK;
                
                // Process selected courses
                if (isset($_POST['MaHP'])) {
                    foreach ($_POST['MaHP'] as $maHP) {
                        $this->chiTietDangKyModel->MaDK = $maDK;
                        $this->chiTietDangKyModel->MaHP = $maHP;
                        $this->chiTietDangKyModel->create();
                    }
                }
                
                header("Location: index.php?controller=dangky&action=index");
                exit();
            }
        }

        $sinhVien = $this->sinhVienModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
        $hocPhan = $this->hocPhanModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
        require 'app/views/dangky/add.php';
    }

    public function view($id) {
        $dangKy = $this->dangKyModel->getById($id);
        $chiTiet = $this->chiTietDangKyModel->getByMaDK($id)->fetchAll(PDO::FETCH_ASSOC);
        $tongTinChi = $this->chiTietDangKyModel->getTotalTinChi($id);
        require 'app/views/dangky/view.php';
    }

    public function delete($id) {
        if ($this->dangKyModel->delete($id)) {
            header("Location: index.php?controller=dangky&action=index");
            exit();
        }
    }

    public function dangKyHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
            $_SESSION['error'] = "Vui lòng đăng nhập để đăng ký học phần!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['maHP'])) {
            $_SESSION['error'] = "Yêu cầu không hợp lệ!";
            header("Location: index.php?controller=hocphan&action=danhsach");
            exit();
        }

        try {
            $maSV = $_SESSION['MaSV'];
            $maHP = $_POST['maHP'];

            // Kiểm tra học phần tồn tại
            $hocPhan = $this->hocPhanModel->getById($maHP);
            if (!$hocPhan) {
                throw new Exception("Học phần không tồn tại!");
            }

            // Kiểm tra số lượng còn lại
            if ($hocPhan['SoLuongDaDangKy'] >= $hocPhan['SoLuongDuKien']) {
                throw new Exception("Học phần đã đầy!");
            }

            // Tạo đăng ký mới
            $this->dangKyModel->NgayDK = date('Y-m-d');
            $this->dangKyModel->MaSV = $maSV;
            
            if ($this->dangKyModel->create()) {
                $maDK = $this->dangKyModel->MaDK;
                
                // Thêm chi tiết đăng ký
                $query = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
                $stmt = $this->db->prepare($query);
                if ($stmt->execute([$maDK, $maHP])) {
                    $_SESSION['success'] = "Đăng ký học phần thành công!";
                } else {
                    throw new Exception("Không thể tạo chi tiết đăng ký!");
                }
            } else {
                throw new Exception("Không thể tạo đăng ký!");
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header("Location: index.php?controller=hocphan&action=danhsach");
        exit();
    }

    public function giohang() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
            $_SESSION['error'] = "Vui lòng đăng nhập để xem giỏ học phần!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $maSV = $_SESSION['MaSV'];
        
        // Lấy danh sách học phần đã đăng ký chờ duyệt
        $query = "SELECT hp.*, dk.TrangThai, dk.NgayDK 
                 FROM HocPhan hp
                 JOIN ChiTietDangKy ct ON hp.MaHP = ct.MaHP
                 JOIN DangKy dk ON ct.MaDK = dk.MaDK
                 WHERE dk.MaSV = ? AND dk.TrangThai = 'Chờ duyệt'
                 ORDER BY dk.NgayDK DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$maSV]);
        $danhSachHocPhan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'app/views/dangky/giohang.php';
    }

    public function huyDangKy() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
            $_SESSION['error'] = "Vui lòng đăng nhập để thực hiện thao tác này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        try {
            $maSV = $_SESSION['MaSV'];
            $maHP = $_GET['maHP'] ?? null;

            if (!$maHP) {
                throw new Exception("Không tìm thấy học phần!");
            }

            // Xóa đăng ký học phần
            $query = "DELETE dk, ct 
                     FROM DangKy dk 
                     JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK 
                     WHERE dk.MaSV = ? 
                     AND ct.MaHP = ? 
                     AND dk.TrangThai = 'Chờ duyệt'";
            
            $stmt = $this->db->prepare($query);
            if ($stmt->execute([$maSV, $maHP])) {
                // Giảm số lượng đã đăng ký của học phần
                $this->hocPhanModel->giamSoLuongDaDangKy($maHP);
                $_SESSION['success'] = "Hủy đăng ký học phần thành công!";
            } else {
                throw new Exception("Không thể hủy đăng ký học phần!");
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header("Location: index.php?controller=dangky&action=giohang");
        exit();
    }

    public function lichsu() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
            $_SESSION['error'] = "Vui lòng đăng nhập để xem lịch sử đăng ký!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $maSV = $_SESSION['MaSV'];
        
        // Lấy danh sách đăng ký của sinh viên
        $query = "SELECT hp.MaHP, hp.TenHP, hp.SoTinChi, 
                        dk.NgayDK, dk.TrangThai
                 FROM DangKy dk
                 JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK
                 JOIN HocPhan hp ON ct.MaHP = hp.MaHP
                 WHERE dk.MaSV = ?
                 ORDER BY dk.NgayDK DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$maSV]);
        $lichSuDangKy = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'app/views/dangky/lichsu.php';
    }
}
