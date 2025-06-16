<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container mt-4">
    <h2>Sửa thông tin sinh viên</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?controller=admin&action=suaSinhVien&id=<?php echo htmlspecialchars($sinhVien['MaSV']); ?>" 
          method="POST" 
          enctype="multipart/form-data">
        
        <div class="mb-3">
            <label for="MaSV" class="form-label">Mã sinh viên</label>
            <input type="text" class="form-control" id="MaSV" value="<?php echo htmlspecialchars($sinhVien['MaSV']); ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="HoTen" class="form-label">Họ tên</label>
            <input type="text" class="form-control" id="HoTen" name="HoTen" 
                   value="<?php echo htmlspecialchars($sinhVien['HoTen']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="GioiTinh" class="form-label">Giới tính</label>
            <select class="form-select" id="GioiTinh" name="GioiTinh" required>
                <option value="">-- Chọn giới tính --</option>
                <option value="Nam" <?php echo $sinhVien['GioiTinh'] == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                <option value="Nữ" <?php echo $sinhVien['GioiTinh'] == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="NgaySinh" class="form-label">Ngày sinh</label>
            <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" 
                   value="<?php echo htmlspecialchars($sinhVien['NgaySinh']); ?>">
        </div>

        <div class="mb-3">
            <label for="MaNganh" class="form-label">Ngành học</label>
            <select class="form-select" id="MaNganh" name="MaNganh" required>
                <option value="">-- Chọn ngành học --</option>
                <?php foreach ($dsNganh as $nganh): ?>
                    <option value="<?php echo htmlspecialchars($nganh['MaNganh']); ?>"
                            <?php echo $sinhVien['MaNganh'] == $nganh['MaNganh'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($nganh['TenNganh']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="MatKhau" class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" id="MatKhau" name="MatKhau" 
                   placeholder="Để trống nếu không thay đổi mật khẩu">
            <div class="form-text">Nếu không nhập mật khẩu mới, mật khẩu cũ sẽ được giữ nguyên.</div>
        </div>

        <div class="mb-3">
            <label for="Hinh" class="form-label">Ảnh sinh viên</label>
            <?php if (!empty($sinhVien['Hinh']) && file_exists($sinhVien['Hinh'])): ?>
                <div class="mb-2">
                    <img src="<?php echo htmlspecialchars($sinhVien['Hinh']); ?>" 
                         alt="Ảnh sinh viên" 
                         class="img-thumbnail"
                         style="max-width: 200px;">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control" id="Hinh" name="Hinh" accept="image/*">
            <div class="form-text">Chọn ảnh mới nếu muốn thay đổi ảnh hiện tại.</div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="index.php?controller=admin&action=quanLySinhVien" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
