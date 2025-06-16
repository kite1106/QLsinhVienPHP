<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sinh viên</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Thêm sinh viên mới</h2>
        
        <form action="index.php?controller=sinhvien&action=add" method="POST" enctype="multipart/form-data" class="mt-3">
            <div class="mb-3">
                <label for="MaSV" class="form-label">Mã sinh viên</label>
                <input type="text" class="form-control" id="MaSV" name="MaSV" required 
                       maxlength="10" pattern="[0-9]+" title="Mã sinh viên chỉ được chứa số">
            </div>
            
            <div class="mb-3">
                <label for="HoTen" class="form-label">Họ tên</label>
                <input type="text" class="form-control" id="HoTen" name="HoTen" required maxlength="50">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Giới tính</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="GioiTinh" id="Nam" value="Nam" required>
                        <label class="form-check-label" for="Nam">Nam</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="GioiTinh" id="Nu" value="Nữ">
                        <label class="form-check-label" for="Nu">Nữ</label>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="NgaySinh" class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" required>
            </div>
            
            <div class="mb-3">
                <label for="Hinh" class="form-label">Hình ảnh</label>
                <input type="file" class="form-control" id="Hinh" name="Hinh" accept="image/*">
            </div>
            
            <div class="mb-3">
                <label for="MaNganh" class="form-label">Ngành học</label>
                <select class="form-control" id="MaNganh" name="MaNganh" required>
                    <option value="">Chọn ngành học</option>
                    <?php foreach ($nganhHoc as $nganh): ?>
                        <option value="<?php echo htmlspecialchars($nganh['MaNganh']); ?>">
                            <?php echo htmlspecialchars($nganh['TenNganh']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="index.php?controller=sinhvien&action=index" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
