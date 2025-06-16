<?php
require_once 'app/models/SinhVienModel.php';
require_once 'app/models/NganhHocModel.php';
require_once 'app/helpers/SessionHelper.php';

class SinhVienController {
    private $sinhVienModel;
    private $nganhHocModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->sinhVienModel = new SinhVienModel($db);
        $this->nganhHocModel = new NganhHocModel($db);
    }

    public function index() {
        $result = $this->sinhVienModel->getAll();
        if ($result === false) {
            $_SESSION['error'] = "Có lỗi xảy ra khi lấy danh sách sinh viên";
            $sinhVien = [];
        } else {
            $sinhVien = $result->fetchAll(PDO::FETCH_ASSOC);
        }
        require 'app/views/sinhvien/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->sinhVienModel->MaSV = $_POST['MaSV'];
            $this->sinhVienModel->HoTen = $_POST['HoTen'];
            $this->sinhVienModel->GioiTinh = $_POST['GioiTinh'];
            $this->sinhVienModel->NgaySinh = $_POST['NgaySinh'];
            $this->sinhVienModel->MaNganh = $_POST['MaNganh'];

            // Handle file upload
            if(isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
                $target_dir = "public/uploads/sinhvien/";
                $file_extension = pathinfo($_FILES["Hinh"]["name"], PATHINFO_EXTENSION);
                $new_filename = uniqid() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
                    $this->sinhVienModel->Hinh = $target_file;
                }
            }

            if ($this->sinhVienModel->create()) {
                header("Location: index.php?controller=sinhvien&action=index");
                exit();
            }
        }

        $nganhHocResult = $this->nganhHocModel->getAll();
        if ($nganhHocResult === false) {
            $_SESSION['error'] = "Có lỗi xảy ra khi lấy danh sách ngành học";
            $nganhHoc = [];
        } else {
            $nganhHoc = $nganhHocResult->fetchAll(PDO::FETCH_ASSOC);
        }
        require 'app/views/sinhvien/add.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->sinhVienModel->MaSV = $id;
            $this->sinhVienModel->HoTen = $_POST['HoTen'];
            $this->sinhVienModel->GioiTinh = $_POST['GioiTinh'];
            $this->sinhVienModel->NgaySinh = $_POST['NgaySinh'];
            $this->sinhVienModel->MaNganh = $_POST['MaNganh'];

            // Handle file upload
            if(isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
                $target_dir = "public/uploads/sinhvien/";
                $file_extension = pathinfo($_FILES["Hinh"]["name"], PATHINFO_EXTENSION);
                $new_filename = uniqid() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
                    $this->sinhVienModel->Hinh = $target_file;
                }
            } else {
                // Keep existing image
                $sinhVien = $this->sinhVienModel->getById($id);
                $this->sinhVienModel->Hinh = $sinhVien['Hinh'];
            }

            if ($this->sinhVienModel->update()) {
                header("Location: index.php?controller=sinhvien&action=index");
                exit();
            }
        }

        $sinhVien = $this->sinhVienModel->getById($id);
        if ($sinhVien === false) {
            $_SESSION['error'] = "Không tìm thấy sinh viên";
            header("Location: index.php?controller=sinhvien&action=index");
            exit();
        }

        $nganhHocResult = $this->nganhHocModel->getAll();
        if ($nganhHocResult === false) {
            $_SESSION['error'] = "Có lỗi xảy ra khi lấy danh sách ngành học";
            $nganhHoc = [];
        } else {
            $nganhHoc = $nganhHocResult->fetchAll(PDO::FETCH_ASSOC);
        }
        require 'app/views/sinhvien/edit.php';
    }

    public function delete($id) {
        if ($this->sinhVienModel->delete($id)) {
            header("Location: index.php?controller=sinhvien&action=index");
            exit();
        }
    }
}
