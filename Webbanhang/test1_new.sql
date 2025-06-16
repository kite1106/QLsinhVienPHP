CREATE DATABASE Test1;
USE Test1;

-- Bảng ngành học
CREATE TABLE NganhHoc (
    MaNganh CHAR(4) PRIMARY KEY,
    TenNganh NVARCHAR(30)
);

-- Bảng sinh viên với mật khẩu
CREATE TABLE SinhVien (
    MaSV CHAR(10) PRIMARY KEY,
    HoTen NVARCHAR(50) NOT NULL,
    GioiTinh NVARCHAR(5),
    NgaySinh DATE,
    Hinh VARCHAR(50),
    MaNganh CHAR(4),
    MatKhau VARCHAR(32) NOT NULL DEFAULT 'e10adc3949ba59abbe56e057f20f883e', -- Default password: 123456
    FOREIGN KEY (MaNganh) REFERENCES NganhHoc(MaNganh)
);

-- Bảng Admin
CREATE TABLE Admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(32) NOT NULL,
    TenAdmin NVARCHAR(50) NOT NULL
);

-- Bảng học phần với số lượng sinh viên
CREATE TABLE HocPhan (
    MaHP CHAR(6) PRIMARY KEY,
    TenHP NVARCHAR(30) NOT NULL,
    SoTinChi INT,
    SoLuongDuKien INT NOT NULL DEFAULT 40,
    SoLuongDaDangKy INT NOT NULL DEFAULT 0
);

-- Bảng đăng ký với trạng thái
CREATE TABLE DangKy (
    MaDK INT AUTO_INCREMENT PRIMARY KEY,
    NgayDK DATE,
    MaSV CHAR(10),
    TrangThai VARCHAR(20) DEFAULT 'pending',
    FOREIGN KEY (MaSV) REFERENCES SinhVien(MaSV)
);

-- Bảng chi tiết đăng ký
CREATE TABLE ChiTietDangKy (
    MaDK INT,
    MaHP CHAR(6),
    PRIMARY KEY (MaDK, MaHP),
    FOREIGN KEY (MaDK) REFERENCES DangKy(MaDK),
    FOREIGN KEY (MaHP) REFERENCES HocPhan(MaHP)
);

-- Dữ liệu ngành học
INSERT INTO NganhHoc (MaNganh, TenNganh) VALUES 
('CNTT', N'Công nghệ thông tin'),
('QTKD', N'Quản trị kinh doanh');

-- Dữ liệu sinh viên (password: 123456)
INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh, MatKhau)
VALUES ('0123456789', N'Nguyễn Văn A', N'Nam', '2000-02-12', '/Content/images/sv1.jpg', 'CNTT', 'e10adc3949ba59abbe56e057f20f883e');

INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh, MatKhau)
VALUES ('9876543210', N'Nguyễn Thị B', N'Nữ', '2000-07-03', '/Content/images/sv2.jpg', 'QTKD', 'e10adc3949ba59abbe56e057f20f883e');

-- Dữ liệu học phần
INSERT INTO HocPhan (MaHP, TenHP, SoTinChi, SoLuongDuKien) VALUES 
('CNTT01', N'Lập trình C', 3, 40),
('CNTT02', N'Cơ sở dữ liệu', 2, 35),
('QTKD01', N'Kinh tế vi mô', 2, 45),
('QTKD02', N'Xác suất thống kê 1', 3, 30);

-- Tài khoản Admin (username: admin, password: admin123)
INSERT INTO Admin (username, password, TenAdmin) VALUES
('admin', '0192023a7bbd73250516f069df18b500', N'Quản trị viên');
