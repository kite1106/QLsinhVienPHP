<?php require_once __DIR__ . '/../../shares/header.php'; ?>

<div class="container mt-4">
    <h2>Thêm sinh viên mới</h2>

    <form action="index.php?controller=admin&action=themSinhVien" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="MaSV" class="form-label">Mã sinh viên</label>
            <input type="text" class="form-control" id="MaSV" name="MaSV" required>
        </div>

        <div class="mb-3">
            <label for="HoTen" class="form-label">Họ tên</label>
            <input type="text" class="form-control" id="HoTen" name="HoTen" required>
        </div>

        <div class="mb-3">
            <label for="GioiTinh" class="form-label">Giới tính</label>
            <select class="form-select" id="GioiTinh" name="GioiTinh" required>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="NgaySinh" class="form-label">Ngày sinh</label>
            <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" required>
        </div>

        <div class="mb-3">
            <label for="MaNganh" class="form-label">Mã ngành</label>
            <input type="text" class="form-control" id="MaNganh" name="MaNganh" required>
        </div>

        <div class="mb-3">
            <label for="MatKhau" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="MatKhau" name="MatKhau" placeholder="Để trống để dùng mật khẩu mặc định">
        </div>

        <div class="mb-3">
            <label for="Hinh" class="form-label">Ảnh</label>
            <input type="file" class="form-control" id="Hinh" name="Hinh">
        </div>

        <button type="submit" class="btn btn-primary">Thêm sinh viên</button>
        <a href="index.php?controller=admin&action=quanLySinhVien" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php require_once __DIR__ . '/../../shares/footer.php'; ?>
