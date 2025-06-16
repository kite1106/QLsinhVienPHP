<?php include 'app/views/shares/header.php'; ?>

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

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã HP</th>
                    <th>Tên học phần</th>
                    <th>Số tín chỉ</th>
                    <th>Số slot còn lại</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($danhSachHocPhan as $hp): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hp['MaHP']); ?></td>
                    <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                    <td><?php echo htmlspecialchars($hp['SoTinChi'] ?? 0); ?></td>
                    <td>
                        <?php 
                        $slotConLai = ($hp['SoLuongDuKien'] ?? 0) - ($hp['SoLuongDaDangKy'] ?? 0);
                        echo $slotConLai;
                        ?>
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
                        <?php if (empty($hp['TrangThai'])): ?>
                            <form action="index.php?controller=hocphan&action=dangKyHocPhan" method="POST" style="display: inline;">
                                <input type="hidden" name="maHP" value="<?php echo $hp['MaHP']; ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Đăng ký</button>
                            </form>
                        <?php elseif ($hp['TrangThai'] == 'Chờ duyệt'): ?>
                            <a href="index.php?controller=dangky&action=huyDangKy&maHP=<?php echo $hp['MaHP']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc muốn hủy đăng ký học phần này?');">
                                Hủy đăng ký
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
