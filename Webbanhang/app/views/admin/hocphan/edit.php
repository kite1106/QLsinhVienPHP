<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container mt-4">
    <h2>Sửa học phần</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?controller=hocphan&action=capNhatHocPhan" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="MaHP" class="form-label">Mã học phần</label>
            <input type="text" class="form-control" id="MaHP" name="MaHP" 
                value="<?php echo htmlspecialchars($hocPhan['MaHP'] ?? ''); ?>" readonly>
            <small class="form-text text-muted">Mã học phần không thể thay đổi</small>
        </div>

        <div class="mb-3">
            <label for="TenHP" class="form-label">Tên học phần *</label>
            <input type="text" class="form-control" id="TenHP" name="TenHP" required
                value="<?php echo htmlspecialchars($hocPhan['TenHP'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="SoTinChi" class="form-label">Số tín chỉ *</label>
            <input type="number" class="form-control" id="SoTinChi" name="SoTinChi" required min="1" max="10"
                value="<?php echo htmlspecialchars($hocPhan['SoTinChi'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="SoLuongDuKien" class="form-label">Sức chứa *</label>
            <input type="number" class="form-control" id="SoLuongDuKien" name="SoLuongDuKien" required min="1"
                value="<?php echo htmlspecialchars($hocPhan['SoLuongDuKien'] ?? ''); ?>">
        </div>

    

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="index.php?controller=admin&action=quanLyHocPhan" class="btn btn-secondary">Hủy</a>
    </form>

    <?php if ($hocPhan['SoLuongDaDangKy'] > 0): ?>
    <div class="alert alert-info mt-4">
        <i class="fas fa-info-circle"></i> Học phần này hiện có <?php echo htmlspecialchars($hocPhan['SoLuongDaDangKy']); ?> sinh viên đã đăng ký.
        Thay đổi sức chứa cần đảm bảo không ảnh hưởng đến các đăng ký hiện có.
    </div>
    <?php endif; ?>
</div>

<script>
// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
