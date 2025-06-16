<?php
require_once 'app/models/SinhVienModel.php';
require_once 'app/models/AdminModel.php';
require_once 'app/helpers/SessionHelper.php';

class AuthController {
    private $sinhVienModel;
    private $adminModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->sinhVienModel = new SinhVienModel($db);
        $this->adminModel = new AdminModel($db);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        // If already logged in, redirect to appropriate dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $userType = $_POST['userType'] ?? 'student';

            // Hash password for security
            $hashedPassword = md5($password);

            $user = null;
            if ($userType === 'student') {
                $user = $this->sinhVienModel->checkLogin($username, $hashedPassword);
                if ($user) {
                    $this->createUserSession($user, 'student');
                    header("Location: index.php?controller=hocphan&action=danhsach");
                    exit();
                }
            } else if ($userType === 'admin') {
                $user = $this->adminModel->checkLogin($username, $hashedPassword);
                if ($user) {
                    $this->createUserSession($user, 'admin');
                    header("Location: index.php?controller=admin&action=dashboard");
                    exit();
                }
            }

            $error = "Tên đăng nhập hoặc mật khẩu không đúng";
            require 'app/views/auth/login.php';
        } else {
            require 'app/views/auth/login.php';
        }
    }

    private function createUserSession($user, $userType) {
        $_SESSION['user_id'] = $userType === 'student' ? $user['MaSV'] : $user['admin_id'];
        $_SESSION['user_name'] = $userType === 'student' ? $user['HoTen'] : $user['TenAdmin'];
        $_SESSION['user_type'] = $userType === 'student' ? 'student' : 'admin';
        $_SESSION['logged_in'] = true;
        
        // Add these for student-specific data
        if ($userType === 'student') {
            $_SESSION['MaSV'] = $user['MaSV'];
            $_SESSION['HoTen'] = $user['HoTen'];
        }
    }

    private function redirectToDashboard() {
        if ($_SESSION['user_type'] === 'student') {
            header("Location: index.php?controller=hocphan&action=danhsach");
        } else {
            header("Location: index.php?controller=admin&action=dashboard");
        }
        exit();
    }

    public function logout() {
        // Destroy all session data
        session_start();
        $_SESSION = array();
        session_destroy();
        
        // Redirect to login page
        header("Location: index.php?controller=auth&action=login");
        exit();
    }

    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    public function requireAdmin() {
        $this->requireLogin();
        if ($_SESSION['user_type'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    public function getLoggedInUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public function getLoggedInUserType() {
        return $_SESSION['user_type'] ?? null;
    }

    public function getLoggedInUserName() {
        return $_SESSION['user_name'] ?? null;
    }
}
