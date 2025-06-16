<?php require_once __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</h2>
        <a href="index.php?controller=admin&action=thongKe" class="btn btn-primary">
            <i class="fas fa-chart-bar"></i> Xem thống kê chi tiết
        </a>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Sinh viên</h5>
                            <h2 class="display-4 mb-0"><?php echo $totalStudents; ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary-dark">
                    <a href="index.php?controller=admin&action=quanLySinhVien" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-right"></i> Quản lý sinh viên
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Học phần</h5>
                            <h2 class="display-4 mb-0"><?php echo $totalCourses; ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-book fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-success-dark">
                    <a href="index.php?controller=admin&action=quanLyHocPhan" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-right"></i> Quản lý học phần
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Đăng ký</h5>
                            <h2 class="display-4 mb-0"><?php echo $totalRegistrations; ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-clipboard-list fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-info-dark">
                    <a href="index.php?controller=admin&action=quanLyDangKy" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-right"></i> Xem đăng ký
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0">
                <i class="fas fa-history"></i> Đăng ký gần đây
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã SV</th>
                            <th>Họ tên</th>
                            <th>Danh sách học phần</th>
                            <th>Ngày đăng ký</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestRegistrations as $reg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reg['MaSV']); ?></td>
                                <td><?php echo htmlspecialchars($reg['HoTen']); ?></td>
                                <td><?php echo htmlspecialchars($reg['DanhSachHP']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($reg['NgayDK'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $reg['TrangThai'] == 'Đã duyệt' ? 'success' : 'warning'; ?>">
                                        <?php echo htmlspecialchars($reg['TrangThai']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?controller=admin&action=xemDangKy&id=<?php echo $reg['MaDK']; ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.bg-primary-dark { background-color: rgba(0,0,0,0.1); }
.bg-success-dark { background-color: rgba(0,0,0,0.1); }
.bg-info-dark { background-color: rgba(0,0,0,0.1); }
.card-footer { border-top: 1px solid rgba(255,255,255,0.1); }
</style>

<?php require_once __DIR__ . '/../shares/footer.php'; ?>
