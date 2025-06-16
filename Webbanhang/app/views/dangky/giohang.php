<?php require_once 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h2>Giỏ học phần đã chọn</h2>

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

    <?php if (empty($danhSachHocPhan)): ?>
        <div class="alert alert-info">
            Bạn chưa đăng ký học phần nào đang chờ duyệt.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên học phần</th>
                        <th>Số tín chỉ</th>
                        <th>Ngày đăng ký</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($danhSachHocPhan as $hp): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hp['MaHP']); ?></td>
                        <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                        <td><?php echo htmlspecialchars($hp['SoTinChi']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($hp['NgayDK'])); ?></td>
                        <td>
                            <span class="badge bg-warning">
                                <?php echo htmlspecialchars($hp['TrangThai']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?controller=dangky&action=huyDangKy&maHP=<?php echo urlencode($hp['MaHP']); ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc muốn hủy đăng ký học phần này?');">
                                Hủy đăng ký
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="mt-3">
        <a href="index.php?controller=hocphan&action=danhsach" class="btn btn-primary">
            Tiếp tục đăng ký học phần
        </a>
    </div>
</div>

<?php require_once 'app/views/shares/footer.php'; ?>
