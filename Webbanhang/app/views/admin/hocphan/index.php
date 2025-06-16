<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container mt-4">
    <h2>Quản lý học phần</h2>
    
    <div class="mb-3">
        <a href="index.php?controller=admin&action=themHocPhan" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm học phần mới
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã HP</th>
                    <th>Tên học phần</th>
                    <th>Số tín chỉ</th>
                    <th>Sức chứa</th>
                    <th>Đã đăng ký</th>
                    <th>Còn trống</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hocPhan as $hp): ?>
                    <?php $slotConLai = $hp['SoLuongDuKien'] - $hp['SoLuongDaDangKy']; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hp['MaHP']); ?></td>
                        <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                        <td><?php echo htmlspecialchars($hp['SoTinChi']); ?></td>
                        <td><?php echo htmlspecialchars($hp['SoLuongDuKien']); ?></td>
                        <td><?php echo htmlspecialchars($hp['SoLuongDaDangKy']); ?></td>
                        <td><?php echo htmlspecialchars($slotConLai); ?></td>
                        <td>
                            <a href="index.php?controller=hocphan&action=suaHocPhan&id=<?php echo $hp['MaHP']; ?>" 
                               class="btn btn-sm btn-warning">Sửa</a>
                            <a href="index.php?controller=hocphan&action=xoaHocPhan&id=<?php echo $hp['MaHP']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa học phần này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
