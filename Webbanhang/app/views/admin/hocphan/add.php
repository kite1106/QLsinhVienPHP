<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container mt-4">
    <h2>Thêm học phần mới</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?controller=admin&action=luuHocPhan" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="MaHP" class="form-label">Mã học phần</label>
            <input type="text" class="form-control" id="MaHP" name="MaHP" required maxlength="6">
        </div>

        <div class="mb-3">
            <label for="TenHP" class="form-label">Tên học phần</label>
            <input type="text" class="form-control" id="TenHP" name="TenHP" required>
        </div>

        <div class="mb-3">
            <label for="SoTinChi" class="form-label">Số tín chỉ</label>
            <input type="number" class="form-control" id="SoTinChi" name="SoTinChi" required min="1" max="10">
        </div>

        <div class="mb-3">
            <label for="SoLuongDuKien" class="form-label">Sức chứa</label>
            <input type="number" class="form-control" id="SoLuongDuKien" name="SoLuongDuKien" required min="1" value="40">
        </div>

        <button type="submit" class="btn btn-primary">Thêm học phần</button>
        <a href="index.php?controller=admin&action=quanLyHocPhan" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
