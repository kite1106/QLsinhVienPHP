<?php require_once __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <h2><i class="fas fa-history"></i> Lịch sử đăng ký học phần</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Mã HP</th>
                    <th>Tên học phần</th>
                    <th>Số tín chỉ</th>
                    <th>Ngày đăng ký</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lichSuDangKy)): ?>
                    <?php foreach ($lichSuDangKy as $dk): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dk['MaHP']); ?></td>
                            <td><?php echo htmlspecialchars($dk['TenHP']); ?></td>
                            <td><?php echo htmlspecialchars($dk['SoTinChi']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($dk['NgayDK'])); ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $dk['TrangThai'] == 'Đã duyệt' ? 'success' : 
                                        ($dk['TrangThai'] == 'Chờ duyệt' ? 'warning' : 'danger'); 
                                ?>">
                                    <?php echo htmlspecialchars($dk['TrangThai']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Chưa có lịch sử đăng ký học phần</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../shares/footer.php'; ?>
