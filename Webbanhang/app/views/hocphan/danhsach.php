<?php require_once __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <h2>Danh sách học phần</h2>
    
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

    <div class="alert alert-info">
        <strong>Lưu ý:</strong> Vui lòng kiểm tra số slot còn lại trước khi đăng ký học phần.
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Mã HP</th>
                    <th>Tên học phần</th>
                    <th>Số tín chỉ</th>
                    <th>Sức chứa</th>
                    <th>Slot còn lại</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($danhSachHocPhan as $hp): ?>
                <?php 
                    $slotConLai = $hp['SucChua'] - ($hp['SoLuongDK'] ?? 0);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($hp['MaHP']); ?></td>
                    <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                    <td><?php echo htmlspecialchars($hp['SoTC']); ?></td>
                    <td><?php echo htmlspecialchars($hp['SucChua']); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $slotConLai > 0 ? 'success' : 'danger'; ?>">
                            <?php echo $slotConLai; ?>
                        </span>
                    </td>
                    <td>
                        <?php if (isset($hp['TrangThai'])): ?>
                            <span class="badge bg-<?php 
                                echo match($hp['TrangThai']) {
                                    'Đã duyệt' => 'success',
                                    'Chờ duyệt' => 'warning',
                                    'Đã hủy' => 'danger',
                                    default => 'secondary'
                                };
                            ?>">
                                <?php echo htmlspecialchars($hp['TrangThai']); ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Chưa đăng ký</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!isset($hp['TrangThai']) && $slotConLai > 0): ?>
                            <form action="index.php?controller=hocphan&action=dangKyHocPhan" method="POST" class="d-inline">
                                <input type="hidden" name="maHP" value="<?php echo htmlspecialchars($hp['MaHP']); ?>">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Đăng ký
                                </button>
                            </form>
                        <?php elseif (isset($hp['TrangThai']) && $hp['TrangThai'] === 'Chờ duyệt'): ?>
                            <a href="index.php?controller=hocphan&action=huyDangKy&maHP=<?php echo urlencode($hp['MaHP']); ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký học phần này?');">
                                <i class="fas fa-times-circle"></i> Hủy đăng ký
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../shares/footer.php'; ?>
