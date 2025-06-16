<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Danh sách sinh viên</h2>
        <a href="index.php?controller=sinhvien&action=add" class="btn btn-primary mb-3">Thêm sinh viên</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Hình</th>
                    <th>Ngành học</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sinhVien as $sv): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sv['MaSV']); ?></td>
                    <td><?php echo htmlspecialchars($sv['HoTen']); ?></td>
                    <td><?php echo htmlspecialchars($sv['GioiTinh']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($sv['NgaySinh'])); ?></td>
                    <td>
                        <?php if ($sv['Hinh']): ?>
                            <img src="<?php echo htmlspecialchars($sv['Hinh']); ?>" 
                                 alt="Ảnh sinh viên" 
                                 style="max-width: 50px;">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($sv['TenNganh']); ?></td>
                    <td>
                        <a href="index.php?controller=sinhvien&action=edit&id=<?php echo $sv['MaSV']; ?>" 
                           class="btn btn-warning btn-sm">Sửa</a>
                        <a href="index.php?controller=sinhvien&action=delete&id=<?php echo $sv['MaSV']; ?>" 
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
