<?php
require_once 'app/models/HocPhanModel.php';
require_once 'app/models/DangKyModel.php';
require_once 'app/models/NganhHocModel.php';
require_once 'app/helpers/SessionHelper.php';

class HocPhanController {
    private $hocPhanModel;
    private $dangKyModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->hocPhanModel = new HocPhanModel($db);
        $this->dangKyModel = new DangKyModel($db);
    }

    // Action cho admin xem danh sách học phần
    public function quanLyHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $result = $this->hocPhanModel->getAll();
        if ($result === false) {
            $_SESSION['error'] = "Có lỗi xảy ra khi lấy danh sách học phần";
            $hocPhan = [];
        } else {
            $hocPhan = $result->fetchAll(PDO::FETCH_ASSOC);
        }
        require 'app/views/admin/hocphan/index.php';
    }

    // Action cho sinh viên xem danh sách học phần có thể đăng ký
    public function danhSachHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'sinhvien') {
            $_SESSION['error'] = "Vui lòng đăng nhập để xem danh sách học phần!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $maSV = $_SESSION['user_id'];
        $result = $this->hocPhanModel->getAvailableCourses($maSV);
        $hocPhan = $result->fetchAll(PDO::FETCH_ASSOC);
        require 'app/views/sinhvien/danhsach_hocphan.php';
    }

    // Action thêm học phần mới (admin)
    public function themHocPhan() {
        // Check admin permission
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        // Show add form
        require 'app/views/admin/hocphan/add.php';
    }

    // Action lưu học phần mới (admin)
    public function luuHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate input
            if (empty($_POST['MaHP']) || empty($_POST['TenHP']) || empty($_POST['SoTinChi'])) {
                $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
                header("Location: index.php?controller=admin&action=themHocPhan");
                exit();
            }

            $this->hocPhanModel->MaHP = $_POST['MaHP'];
            $this->hocPhanModel->TenHP = $_POST['TenHP'];
            $this->hocPhanModel->SoTinChi = intval($_POST['SoTinChi']);
            $this->hocPhanModel->SoLuongDuKien = isset($_POST['SoLuongDuKien']) ? intval($_POST['SoLuongDuKien']) : 40;

            if ($this->hocPhanModel->create()) {
                $_SESSION['success'] = "Thêm học phần thành công!";
                header("Location: index.php?controller=admin&action=quanLyHocPhan");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi thêm học phần!";
                header("Location: index.php?controller=admin&action=themHocPhan");
                exit();
            }
        }
    }

    // Action sửa học phần (admin)
    public function suaHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = "Không tìm thấy học phần!";
            header("Location: index.php?controller=admin&action=quanLyHocPhan");
            exit();
        }

        $hocPhan = $this->hocPhanModel->getById($id);
        if (!$hocPhan) {
            $_SESSION['error'] = "Không tìm thấy học phần!";
            header("Location: index.php?controller=admin&action=quanLyHocPhan");
            exit();
        }

        require 'app/views/admin/hocphan/edit.php';
    }

    // Action cập nhật học phần (admin)
    public function capNhatHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if (empty($_POST['MaHP']) || empty($_POST['TenHP']) || empty($_POST['SoTinChi']) || empty($_POST['SoLuongDuKien'])) {
                    throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc!");
                }

                $this->hocPhanModel->MaHP = $_POST['MaHP'];
                $this->hocPhanModel->TenHP = $_POST['TenHP'];
                $this->hocPhanModel->SoTinChi = intval($_POST['SoTinChi']);
                $this->hocPhanModel->SoLuongDuKien = intval($_POST['SoLuongDuKien']);
                $this->hocPhanModel->MoTa = $_POST['MoTa'] ?? '';  // Add this line

                if ($this->hocPhanModel->update()) {
                    $_SESSION['success'] = "Cập nhật học phần thành công!";
                    header("Location: index.php?controller=admin&action=quanLyHocPhan");
                } else {
                    throw new Exception("Có lỗi xảy ra khi cập nhật học phần!");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: index.php?controller=admin&action=suaHocPhan&id=" . $_POST['MaHP']);
            }
            exit();
        }
    }

    // Action xóa học phần (admin)
    public function xoaHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception("Không tìm thấy học phần!");
            }

            // Xóa học phần
            if ($this->hocPhanModel->delete($id)) {
                $_SESSION['success'] = "Xóa học phần thành công!";
            } else {
                throw new Exception("Có lỗi xảy ra khi xóa học phần!");
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header("Location: index.php?controller=admin&action=quanLyHocPhan");
        exit();
    }

    // Action cho sinh viên xem danh sách học phần để đăng ký
    public function danhsach() {
        // Kiểm tra đăng nhập sinh viên
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
            $_SESSION['error'] = "Vui lòng đăng nhập để xem danh sách học phần!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $maSV = $_SESSION['user_id'];
        $danhSachHocPhan = $this->hocPhanModel->getDanhSachHocPhanChoSinhVien($maSV);

        require 'app/views/sinhvien/danhsach_hocphan.php';
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

        $maSV = $_SESSION['MaSV'];
        $maHP = $_POST['maHP'];

        try {
            // Check if course is available
            $hocPhan = $this->hocPhanModel->getById($maHP);
            if (!$hocPhan) {
                throw new Exception("Học phần không tồn tại!");
            }

            // Check if there are available slots
            $slotConLai = $hocPhan['SoLuongDuKien'] - $hocPhan['SoLuongDaDangKy'];
            if ($slotConLai <= 0) {
                throw new Exception("Học phần đã đầy!");
            }

            // Create registration
            $dangKy = new DangKyModel($this->db);
            if ($dangKy->dangKyHocPhan($maSV, $maHP)) {
                $_SESSION['success'] = "Đăng ký học phần thành công!";
            } else {
                throw new Exception("Không thể đăng ký học phần!");
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header("Location: index.php?controller=hocphan&action=danhsach");
        exit();
    }
}

