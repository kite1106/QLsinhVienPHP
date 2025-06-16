<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý sinh viên</h2>
        <a href="index.php?controller=admin&action=themSinhVien" class="btn btn-primary">Thêm sinh viên mới</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Họ tên</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Ngành học</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sinhVien as $sv): ?>
            <tr>
                <td><?php echo htmlspecialchars($sv['MaSV']); ?></td>
                <td><?php echo htmlspecialchars($sv['HoTen']); ?></td>
                <td><?php echo htmlspecialchars($sv['GioiTinh']); ?></td>
                <td><?php echo htmlspecialchars($sv['NgaySinh']); ?></td>
                <td><?php echo htmlspecialchars($sv['TenNganh'] ?? $sv['MaNganh']); ?></td>
                <td>
                    <a href="index.php?controller=admin&action=xemChiTiet&id=<?php echo $sv['MaSV']; ?>" 
                       class="btn btn-info btn-sm" title="Xem chi tiết">
                        <i class="fas fa-eye">Xem chi tiết</i>
                    </a>
                    <a href="index.php?controller=admin&action=suaSinhVien&id=<?php echo $sv['MaSV']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                    <a href="index.php?controller=admin&action=xoaSinhVien&id=<?php echo $sv['MaSV']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
