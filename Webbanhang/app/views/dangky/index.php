<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đăng ký học phần</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Danh sách đăng ký học phần</h2>
        <a href="index.php?controller=dangky&action=add" class="btn btn-primary mb-3">Thêm đăng ký</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã ĐK</th>
                    <th>Ngày đăng ký</th>
                    <th>Sinh viên</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dangKy as $dk): ?>
                <tr>
                    <td><?php echo htmlspecialchars($dk['MaDK']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($dk['NgayDK'])); ?></td>
                    <td><?php echo htmlspecialchars($dk['HoTen']); ?></td>
                    <td>
                        <a href="index.php?controller=dangky&action=view&id=<?php echo $dk['MaDK']; ?>" 
                           class="btn btn-info btn-sm">Xem chi tiết</a>
                        <a href="index.php?controller=dangky&action=delete&id=<?php echo $dk['MaDK']; ?>" 
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
