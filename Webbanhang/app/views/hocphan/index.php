<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách học phần</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Danh sách học phần</h2>
        <a href="index.php?controller=hocphan&action=add" class="btn btn-primary mb-3">Thêm học phần</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã HP</th>
                    <th>Tên học phần</th>
                    <th>Số tín chỉ</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hocPhan as $hp): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hp['MaHP']); ?></td>
                    <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                    <td><?php echo htmlspecialchars($hp['SoTinChi']); ?></td>
                    <td>
                        <a href="index.php?controller=hocphan&action=edit&id=<?php echo $hp['MaHP']; ?>" 
                           class="btn btn-warning btn-sm">Sửa</a>
                        <a href="index.php?controller=hocphan&action=delete&id=<?php echo $hp['MaHP']; ?>" 
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
