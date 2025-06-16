<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm học phần</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Thêm học phần mới</h2>
        
        <form action="index.php?controller=hocphan&action=add" method="POST" class="mt-3">
            <div class="mb-3">
                <label for="MaHP" class="form-label">Mã học phần</label>
                <input type="text" class="form-control" id="MaHP" name="MaHP" required 
                       maxlength="6" pattern="[A-Za-z0-9]+" 
                       title="Mã học phần chỉ được chứa chữ cái và số">
            </div>
            
            <div class="mb-3">
                <label for="TenHP" class="form-label">Tên học phần</label>
                <input type="text" class="form-control" id="TenHP" name="TenHP" required maxlength="30">
            </div>
            
            <div class="mb-3">
                <label for="SoTinChi" class="form-label">Số tín chỉ</label>
                <input type="number" class="form-control" id="SoTinChi" name="SoTinChi" 
                       required min="1" max="10">
            </div>
            
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="index.php?controller=hocphan&action=index" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
