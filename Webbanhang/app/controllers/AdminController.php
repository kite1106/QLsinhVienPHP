<?php
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/SinhVienModel.php';
require_once __DIR__ . '/../models/HocPhanModel.php';
require_once __DIR__ . '/../models/DangKyModel.php';
require_once __DIR__ . '/../models/NganhHocModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class AdminController {
    private $adminModel;
    private $sinhVienModel;
    private $hocPhanModel;
    private $dangKyModel;
    private $nganhHocModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->adminModel = new AdminModel($db);
        $this->sinhVienModel = new SinhVienModel($db);
        $this->hocPhanModel = new HocPhanModel($db);
        $this->dangKyModel = new DangKyModel($db);
        $this->nganhHocModel = new NganhHocModel($db);

        // Kiểm tra quyền admin
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    public function dashboard() {
        // Lấy thống kê
        $totalStudents = $this->sinhVienModel->getTotalCount();
        $totalCourses = $this->hocPhanModel->getTotalCount(); // Add this line
        $totalRegistrations = $this->dangKyModel->getTotalCount();
        
        // Lấy danh sách đăng ký mới nhất
        $latestRegistrations = $this->dangKyModel->getLatestRegistrations(5);
        
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function quanLySinhVien() {
        $sinhVien = $this->sinhVienModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/admin/sinhvien/index.php';
    }

    public function themSinhVien() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Validate required fields
                if (empty($_POST['MaSV']) || empty($_POST['HoTen']) || empty($_POST['MaNganh'])) {
                    throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc!");
                }

                // Set basic student info
                $this->sinhVienModel->MaSV = $_POST['MaSV'];
                $this->sinhVienModel->HoTen = $_POST['HoTen'];
                $this->sinhVienModel->GioiTinh = $_POST['GioiTinh'] ?? '';
                $this->sinhVienModel->NgaySinh = $_POST['NgaySinh'] ?? null;
                $this->sinhVienModel->MaNganh = $_POST['MaNganh'];
                $this->sinhVienModel->MatKhau = $_POST['MatKhau'] ?? '123456';

                // Handle file upload
                if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
                    // Create upload directory if it doesn't exist
                    $target_dir = "public/uploads/sinhvien";
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }

                    // Generate unique filename
                    $file_extension = strtolower(pathinfo($_FILES["Hinh"]["name"], PATHINFO_EXTENSION));
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_file = $target_dir . '/' . $new_filename;

                    // Validate file type
                    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($file_extension, $allowed_types)) {
                        throw new Exception("Chỉ chấp nhận file ảnh định dạng: " . implode(', ', $allowed_types));
                    }

                    // Move uploaded file
                    if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
                        $this->sinhVienModel->Hinh = $target_file;
                    } else {
                        throw new Exception("Không thể upload file ảnh. Vui lòng thử lại!");
                    }
                }

                if ($this->sinhVienModel->create()) {
                    $_SESSION['success'] = "Thêm sinh viên thành công!";
                    header("Location: index.php?controller=admin&action=quanLySinhVien");
                    exit();
                } else {
                    $_SESSION['error'] = "Không thể thêm sinh viên!";
                }
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        }

        // Lấy danh sách ngành học cho form
        $dsNganh = $this->nganhHocModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/admin/sinhvien/add.php';
    }

    public function suaSinhVien() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=admin&action=quanLySinhVien");
            exit();
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Validate required fields
                if (empty($_POST['HoTen']) || empty($_POST['MaNganh'])) {
                    throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc!");
                }

                // Prepare data for update
                $data = [
                    'MaSV' => $id,
                    'HoTen' => $_POST['HoTen'],
                    'GioiTinh' => $_POST['GioiTinh'],
                    'NgaySinh' => $_POST['NgaySinh'],
                    'MaNganh' => $_POST['MaNganh']
                ];

                // Handle password update
                if (!empty($_POST['MatKhau'])) {
                    $data['MatKhau'] = $_POST['MatKhau'];
                }

                // Handle file upload
                if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
                    // Create upload directory if it doesn't exist
                    $target_dir = "public/uploads/sinhvien";
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }

                    // Generate unique filename
                    $file_extension = strtolower(pathinfo($_FILES["Hinh"]["name"], PATHINFO_EXTENSION));
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_file = $target_dir . '/' . $new_filename;

                    // Validate file type
                    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($file_extension, $allowed_types)) {
                        throw new Exception("Chỉ chấp nhận file ảnh định dạng: " . implode(', ', $allowed_types));
                    }

                    // Delete old image if exists
                    $sinhVien = $this->sinhVienModel->getById($id);
                    if ($sinhVien && !empty($sinhVien['Hinh']) && file_exists($sinhVien['Hinh'])) {
                        unlink($sinhVien['Hinh']);
                    }

                    // Move uploaded file
                    if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
                        $data['Hinh'] = $target_file;
                    } else {
                        throw new Exception("Không thể upload file ảnh. Vui lòng thử lại!");
                    }
                }

                // Update student info
                if ($this->sinhVienModel->update($data)) {
                    $_SESSION['success'] = "Cập nhật thông tin sinh viên thành công!";
                    header("Location: index.php?controller=admin&action=quanLySinhVien");
                    exit();
                } else {
                    throw new Exception("Không thể cập nhật thông tin sinh viên!");
                }
            }

            // Get student info for form
            $sinhVien = $this->sinhVienModel->getById($id);
            if (!$sinhVien) {
                throw new Exception("Không tìm thấy sinh viên!");
            }

            // Get list of majors for form
            $dsNganh = $this->nganhHocModel->getAll()->fetchAll(PDO::FETCH_ASSOC);
            require __DIR__ . '/../views/admin/sinhvien/edit.php';

        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
            header("Location: index.php?controller=admin&action=quanLySinhVien");
            exit();
        }
    }

    public function xoaSinhVien() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controller=admin&action=quanLySinhVien");
            exit();
        }

        try {
            if ($this->sinhVienModel->delete($id)) {
                $_SESSION['success'] = "Xóa sinh viên thành công!";
            } else {
                $_SESSION['error'] = "Không thể xóa sinh viên đã có đăng ký học phần!";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        }

        header("Location: index.php?controller=admin&action=quanLySinhVien");
        exit();
    }

    public function quanLyHocPhan() {
        try {
            $result = $this->hocPhanModel->getAll();
            if ($result === false) {
                $_SESSION['error'] = "Có lỗi xảy ra khi lấy danh sách học phần";
                $hocPhan = [];
            } else {
                $hocPhan = $result->fetchAll(PDO::FETCH_ASSOC);
                if (empty($hocPhan)) {
                    $_SESSION['info'] = "Chưa có học phần nào trong hệ thống";
                }
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            $hocPhan = [];
        }
        require __DIR__ . '/../views/admin/hocphan/index.php';
    }

    public function themHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        require 'app/views/admin/hocphan/add.php';
    }

    public function luuHocPhan() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if (empty($_POST['MaHP']) || empty($_POST['TenHP']) || empty($_POST['SoTinChi'])) {
                    throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc!");
                }

                $hocPhanModel = new HocPhanModel($this->db);
                $hocPhanModel->MaHP = $_POST['MaHP'];
                $hocPhanModel->TenHP = $_POST['TenHP'];
                $hocPhanModel->SoTinChi = intval($_POST['SoTinChi']);
                $hocPhanModel->SoLuongDuKien = isset($_POST['SoLuongDuKien']) ? intval($_POST['SoLuongDuKien']) : 40;

                if ($hocPhanModel->create()) {
                    $_SESSION['success'] = "Thêm học phần thành công!";
                    header("Location: index.php?controller=admin&action=quanLyHocPhan");
                } else {
                    throw new Exception("Có lỗi xảy ra khi thêm học phần!");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: index.php?controller=admin&action=themHocPhan");
            }
            exit();
        }
    }

    public function quanLyDangKy() {
        $dangKy = $this->dangKyModel->getAllWithDetails();
        require __DIR__ . '/../views/admin/dangky/index.php';
    }

    public function duyetDangKy() {
        $id = $_GET['id'] ?? null;
        if ($id && $this->dangKyModel->approve($id)) {
            $_SESSION['success'] = "Đã duyệt đăng ký thành công!";
        } else {
            $_SESSION['error'] = "Không thể duyệt đăng ký!";
        }
        header("Location: index.php?controller=admin&action=quanLyDangKy");
        exit();
    }

    public function huyDangKy() {
        $id = $_GET['id'] ?? null;
        if ($id && $this->dangKyModel->cancel($id)) {
            $_SESSION['success'] = "Đã hủy đăng ký thành công!";
        } else {
            $_SESSION['error'] = "Không thể hủy đăng ký!";
        }
        header("Location: index.php?controller=admin&action=quanLyDangKy");
        exit();
    }

    public function thongKe() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $dangKyModel = new DangKyModel($this->db);
        
        // Get statistics
        $thongKeNganh = $dangKyModel->getThongKeTheoNganh();
        $thongKeHocPhan = $dangKyModel->getThongKeTheoHocPhan();

        require 'app/views/admin/thongke.php';
    }

    public function xemChiTiet() {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        try {
            $maSV = $_GET['id'] ?? null;
            if (!$maSV) {
                throw new Exception("Không tìm thấy sinh viên!");
            }

            // Lấy thông tin sinh viên kèm thông tin ngành
            $query = "SELECT sv.*, nh.TenNganh 
                     FROM SinhVien sv 
                     LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh 
                     WHERE sv.MaSV = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$maSV]);
            $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$sinhVien) {
                throw new Exception("Không tìm thấy thông tin sinh viên!");
            }

            // Lấy lịch sử đăng ký học phần
            $query = "SELECT hp.MaHP, hp.TenHP, hp.SoTinChi, dk.NgayDK, dk.TrangThai
                     FROM DangKy dk
                     JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK
                     JOIN HocPhan hp ON ct.MaHP = hp.MaHP
                     WHERE dk.MaSV = ?
                     ORDER BY dk.NgayDK DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$maSV]);
            $danhSachHocPhan = $stmt->fetchAll(PDO::FETCH_ASSOC);

            require 'app/views/admin/sinhvien/chitiet.php';

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?controller=admin&action=quanLySinhVien");
            exit();
        }
    }
}
