<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký học phần</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Đăng ký học phần mới</h2>
        
        <form action="index.php?controller=dangky&action=add" method="POST" class="mt-3">
            <div class="mb-3">
                <label for="MaSV" class="form-label">Sinh viên</label>
                <select class="form-control" id="MaSV" name="MaSV" required>
                    <option value="">Chọn sinh viên</option>
                    <?php foreach ($sinhVien as $sv): ?>
                        <option value="<?php echo htmlspecialchars($sv['MaSV']); ?>">
                            <?php echo htmlspecialchars($sv['MaSV'] . ' - ' . $sv['HoTen']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Chọn học phần</label>
                <div class="row">
                    <?php foreach ($hocPhan as $hp): ?>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="MaHP[]" value="<?php echo htmlspecialchars($hp['MaHP']); ?>" 
                                       id="hp_<?php echo htmlspecialchars($hp['MaHP']); ?>">
                                <label class="form-check-label" for="hp_<?php echo htmlspecialchars($hp['MaHP']); ?>">
                                    <?php echo htmlspecialchars($hp['TenHP'] . ' (' . $hp['SoTinChi'] . ' tín chỉ)'); ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Đăng ký</button>
            <a href="index.php?controller=dangky&action=index" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
