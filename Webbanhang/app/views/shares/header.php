<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống đăng ký học phần</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Đăng ký học phần</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=admin&action=dashboard">🏠 Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=admin&action=quanLySinhVien">👥 Quản lý sinh viên</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=admin&action=quanLyHocPhan">📚 Quản lý học phần</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=admin&action=quanLyDangKy">📝 Quản lý đăng ký</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=admin&action=thongKe">📊 Thống kê</a>
                        </li>
                    <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=hocphan&action=danhsach">📚 Danh sách học phần</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=dangky&action=lichsu">📋 Lịch sử đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_type'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-white">
                                👤 <?php 
                                    echo $_SESSION['user_type'] === 'admin' 
                                        ? 'Admin: ' . ($_SESSION['TenAdmin'] ?? 'Administrator')
                                        : 'Sinh viên: ' . ($_SESSION['HoTen'] ?? $_SESSION['MaSV']);
                                ?>
                            </span>
                        </li>
                        <?php if ($_SESSION['user_type'] === 'student'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?controller=dangky&action=giohang">
                                    📚 Học phần đã chọn
                                    <span class="badge badge-light"><?= count($_SESSION['cart'] ?? []); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=auth&action=logout">🚪 Đăng xuất</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=auth&action=login">🔑 Đăng nhập</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">