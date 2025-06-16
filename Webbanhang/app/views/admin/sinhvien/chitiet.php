<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user"></i> Thông tin sinh viên</h2>
        <a href="index.php?controller=admin&action=quanLySinhVien" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Thông tin cá nhân -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <?php if (!empty($sinhVien['Hinh']) && file_exists($sinhVien['Hinh'])): ?>
                            <img src="<?php echo htmlspecialchars($sinhVien['Hinh']); ?>" 
                                 alt="Ảnh sinh viên" 
                                 class="rounded-circle img-thumbnail"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <img src="public/images/avatar-default.png" 
                                 alt="Ảnh mặc định" 
                                 class="rounded-circle img-thumbnail"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        <?php endif; ?>
                    </div>

                    <table class="table table-borderless">
                        <tr>
                            <th>Mã SV:</th>
                            <td><?php echo htmlspecialchars($sinhVien['MaSV']); ?></td>
                        </tr>
                        <tr>
                            <th>Họ tên:</th>
                            <td><?php echo htmlspecialchars($sinhVien['HoTen']); ?></td>
                        </tr>
                        <tr>
                            <th>Ngành:</th>
                            <td><?php echo htmlspecialchars($sinhVien['TenNganh'] ?? 'Chưa có'); ?></td>
                        </tr>
                        <tr>
                            <th>Giới tính:</th>
                            <td><?php echo htmlspecialchars($sinhVien['GioiTinh']); ?></td>
                        </tr>
                        <tr>
                            <th>Ngày sinh:</th>
                            <td><?php echo date('d/m/Y', strtotime($sinhVien['NgaySinh'])); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Danh sách học phần đã đăng ký -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lịch sử đăng ký học phần</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                <?php if (!empty($danhSachHocPhan)): ?>
                                    <?php foreach ($danhSachHocPhan as $hp): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($hp['MaHP']); ?></td>
                                            <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                                            <td><?php echo htmlspecialchars($hp['SoTinChi']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($hp['NgayDK'])); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $hp['TrangThai'] == 'Đã duyệt' ? 'success' : 
                                                        ($hp['TrangThai'] == 'Chờ duyệt' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo htmlspecialchars($hp['TrangThai']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Chưa có đăng ký học phần nào</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
