<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đăng ký học phần</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Chi tiết đăng ký học phần</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Thông tin đăng ký</h5>
                <p><strong>Mã đăng ký:</strong> <?php echo htmlspecialchars($dangKy['MaDK']); ?></p>
                <p><strong>Ngày đăng ký:</strong> <?php echo date('d/m/Y', strtotime($dangKy['NgayDK'])); ?></p>
                <p><strong>Sinh viên:</strong> <?php echo htmlspecialchars($dangKy['HoTen']); ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Danh sách học phần đã đăng ký</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã HP</th>
                            <th>Tên học phần</th>
                            <th>Số tín chỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chiTiet as $ct): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ct['MaHP']); ?></td>
                            <td><?php echo htmlspecialchars($ct['TenHP']); ?></td>
                            <td><?php echo htmlspecialchars($ct['SoTinChi']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end"><strong>Tổng số tín chỉ:</strong></td>
                            <td><strong><?php echo $tongTinChi; ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="index.php?controller=dangky&action=index" class="btn btn-primary">Quay lại</a>
        </div>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
