<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hệ thống đăng ký học phần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: white;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            font-size: 24px;
            color: #2c3e50;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h1>Đăng nhập hệ thống</h1>
                <p class="text-muted">Đăng nhập để đăng ký học phần</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=auth&action=login" method="POST">
                <div class="form-group">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           required placeholder="Nhập mã sinh viên">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           required placeholder="Nhập mật khẩu">
                </div>

                <div class="form-group">
                    <label class="form-label">Vai trò</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="userType" 
                               id="studentRole" value="student" checked>
                        <label class="form-check-label" for="studentRole">
                            Sinh viên
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="userType" 
                               id="adminRole" value="admin">
                        <label class="form-check-label" for="adminRole">
                            Quản trị viên
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-login">Đăng nhập</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
