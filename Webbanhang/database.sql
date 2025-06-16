CREATE DATABASE Test1;
USE Test1;

-- Create NganhHoc table (Majors)
CREATE TABLE NganhHoc (
    MaNganh CHAR(4) PRIMARY KEY,
    TenNganh NVARCHAR(30)
);

-- Create SinhVien table (Students) with password
CREATE TABLE SinhVien (
    MaSV CHAR(10) PRIMARY KEY,
    HoTen NVARCHAR(50) NOT NULL,
    GioiTinh NVARCHAR(5),
    NgaySinh DATE,
    Hinh VARCHAR(50),
    MaNganh CHAR(4),
    MatKhau VARCHAR(32) NOT NULL,  -- Using MD5 hash (32 characters)
    FOREIGN KEY (MaNganh) REFERENCES NganhHoc(MaNganh)
);

-- Create HocPhan table (Courses) with student limit
CREATE TABLE HocPhan (
    MaHP CHAR(6) PRIMARY KEY,
    TenHP NVARCHAR(30) NOT NULL,
    SoTinChi INT,
    SoLuongDuKien INT NOT NULL DEFAULT 40,
    SoLuongDaDangKy INT NOT NULL DEFAULT 0
);

-- Create DangKy table (Registration)
CREATE TABLE DangKy (
    MaDK INT AUTO_INCREMENT PRIMARY KEY,
    NgayDK DATE,
    MaSV CHAR(10),
    TrangThai VARCHAR(20) DEFAULT 'pending',
    FOREIGN KEY (MaSV) REFERENCES SinhVien(MaSV)
);

-- Create ChiTietDangKy table (Registration Details)
CREATE TABLE ChiTietDangKy (
    MaDK INT,
    MaHP CHAR(6),
    PRIMARY KEY (MaDK, MaHP),
    FOREIGN KEY (MaDK) REFERENCES DangKy(MaDK),
    FOREIGN KEY (MaHP) REFERENCES HocPhan(MaHP)
);

-- Create Admin table
CREATE TABLE Admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(32) NOT NULL,
    TenAdmin NVARCHAR(50) NOT NULL
);

-- Insert sample data
INSERT INTO NganhHoc (MaNganh, TenNganh) VALUES
('CNTT', N'Công nghệ thông tin'),
('QTKD', N'Quản trị kinh doanh');

-- Insert students with password '123456' (MD5 hash: e10adc3949ba59abbe56e057f20f883e)
INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh, MatKhau) VALUES
('0123456789', N'Nguyễn Văn A', N'Nam', '2000-02-12', '/Content/images/sv1.jpg', 'CNTT', 'e10adc3949ba59abbe56e057f20f883e'),
('9876543210', N'Nguyễn Thị B', N'Nữ', '2000-07-03', '/Content/images/sv2.jpg', 'QTKD', 'e10adc3949ba59abbe56e057f20f883e');

-- Insert courses with student limits
INSERT INTO HocPhan (MaHP, TenHP, SoTinChi, SoLuongDuKien) VALUES
('CNTT01', N'Lập trình C', 3, 40),
('CNTT02', N'Cơ sở dữ liệu', 2, 35),
('QTKD01', N'Kinh tế vi mô', 2, 45),
('QTKD02', N'Xác suất thống kê 1', 3, 30);

-- Insert admin account (username: admin, password: admin123) (MD5 hash: 0192023a7bbd73250516f069df18b500)
INSERT INTO Admin (username, password, TenAdmin) VALUES
('admin', '0192023a7bbd73250516f069df18b500', N'Quản trị viên');
