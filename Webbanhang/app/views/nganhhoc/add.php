<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm ngành học</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Thêm ngành học mới</h2>
        
        <form action="index.php?controller=nganhhoc&action=add" method="POST" class="mt-3">
            <div class="mb-3">
                <label for="MaNganh" class="form-label">Mã ngành</label>
                <input type="text" class="form-control" id="MaNganh" name="MaNganh" required 
                       maxlength="4" pattern="[A-Za-z0-9]+" title="Mã ngành chỉ được chứa chữ cái và số">
            </div>
            
            <div class="mb-3">
                <label for="TenNganh" class="form-label">Tên ngành</label>
                <input type="text" class="form-control" id="TenNganh" name="TenNganh" required maxlength="30">
            </div>
            
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="index.php?controller=nganhhoc&action=index" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
