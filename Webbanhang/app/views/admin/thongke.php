<?php require_once __DIR__ . '/../shares/header.php'; ?>

<div class="container mt-4">
    <h2>Thống kê đăng ký học phần</h2>

    <div class="mt-4">
        <h3>Theo ngành học</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Ngành</th>
                    <th>Số lượng đăng ký</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($thongKeNganh)): ?>
                    <?php foreach ($thongKeNganh as $nganh): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nganh['TenNganh']); ?></td>
                            <td><?php echo htmlspecialchars($nganh['SoLuongDangKy']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2">Không có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <h3>Theo học phần</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Học phần</th>
                    <th>Số lượng đăng ký</th>
                    <th>Sức chứa</th>
                    <th>Còn trống</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($thongKeHocPhan)): ?>
                    <?php foreach ($thongKeHocPhan as $hp): ?>
                        <?php $conTrong = $hp['SoLuongDuKien'] - $hp['SoLuongDangKy']; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                            <td><?php echo htmlspecialchars($hp['SoLuongDangKy']); ?></td>
                            <td><?php echo htmlspecialchars($hp['SoLuongDuKien']); ?></td>
                            <td><?php echo htmlspecialchars($conTrong); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Không có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../shares/footer.php'; ?>
