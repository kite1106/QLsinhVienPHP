<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container mt-4">
    <h2>Quản lý đăng ký học phần</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Họ tên SV</th>                <th>Mã SV</th>
                <th>Họ tên</th>
                <th>Danh sách học phần</th>
                <th>Ngày đăng ký</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dangKy as $dk): ?>
            <tr>
                <td><?php echo htmlspecialchars($dk['MaSV']); ?></td>
                <td><?php echo htmlspecialchars($dk['HoTen']); ?></td>                <td><?php echo htmlspecialchars($dk['MaSV']); ?></td>
                <td><?php echo htmlspecialchars($dk['HoTen']); ?></td>
                <td><?php echo htmlspecialchars($dk['DanhSachHP']); ?></td>
                <td><?php echo htmlspecialchars($dk['NgayDK']); ?></td>
                <td><?php echo htmlspecialchars($dk['TrangThai']); ?></td>
                <td>
                    <?php if ($dk['TrangThai'] == 'Chờ duyệt'): ?>
                        <a href="index.php?controller=admin&action=duyetDangKy&id=<?php echo $dk['MaDK']; ?>" 
                           class="btn btn-sm btn-success">Duyệt</a>
                        <a href="index.php?controller=admin&action=huyDangKy&id=<?php echo $dk['MaDK']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc muốn hủy đăng ký này?')">Hủy</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
