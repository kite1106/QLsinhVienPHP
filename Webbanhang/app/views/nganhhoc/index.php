<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách ngành học</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Danh sách ngành học</h2>
        <a href="index.php?controller=nganhhoc&action=add" class="btn btn-primary mb-3">Thêm ngành học</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã ngành</th>
                    <th>Tên ngành</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nganhHoc as $nganh): ?>
                <tr>
                    <td><?php echo htmlspecialchars($nganh['MaNganh']); ?></td>
                    <td><?php echo htmlspecialchars($nganh['TenNganh']); ?></td>
                    <td>
                        <a href="index.php?controller=nganhhoc&action=edit&id=<?php echo $nganh['MaNganh']; ?>" 
                           class="btn btn-warning btn-sm">Sửa</a>
                        <a href="index.php?controller=nganhhoc&action=delete&id=<?php echo $nganh['MaNganh']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
