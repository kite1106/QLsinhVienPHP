-- Create database
CREATE DATABASE Test1;
USE Test1;

-- Create NganhHoc table (Majors)
CREATE TABLE NganhHoc (
    MaNganh CHAR(4) PRIMARY KEY,
    TenNganh NVARCHAR(30)
);

-- Create SinhVien table (Students)
CREATE TABLE SinhVien (
    MaSV CHAR(10) PRIMARY KEY,
    HoTen NVARCHAR(50) NOT NULL,
    GioiTinh NVARCHAR(5),
    NgaySinh DATE,
    Hinh VARCHAR(50),
    MaNganh CHAR(4),
    FOREIGN KEY (MaNganh) REFERENCES NganhHoc(MaNganh)
);

-- Create HocPhan table (Courses)
CREATE TABLE HocPhan (
    MaHP CHAR(6) PRIMARY KEY,
    TenHP NVARCHAR(30) NOT NULL,
    SoTinChi INT
);

-- Create DangKy table (Registration)
CREATE TABLE DangKy (
    MaDK INT AUTO_INCREMENT PRIMARY KEY,
    NgayDK DATE,
    MaSV CHAR(10),
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

-- Sample data for NganhHoc
INSERT INTO NganhHoc (MaNganh, TenNganh) VALUES ('CNTT', N'Công nghệ thông tin');
INSERT INTO NganhHoc (MaNganh, TenNganh) VALUES ('QTKD', N'Quản trị kinh doanh');

-- Sample data for SinhVien
INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh)
VALUES ('0123456789', N'Nguyễn Văn A', N'Nam', '2000-02-12', '/Content/images/sv1.jpg', 'CNTT');

INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh)
VALUES ('9876543210', N'Nguyễn Thị B', N'Nữ', '2000-07-03', '/Content/images/sv2.jpg', 'QTKD');

-- Sample data for HocPhan
INSERT INTO HocPhan (MaHP, TenHP, SoTinChi) VALUES ('CNTT01', N'Lập trình C', 3);
INSERT INTO HocPhan (MaHP, TenHP, SoTinChi) VALUES ('CNTT02', N'Cơ sở dữ liệu', 2);
INSERT INTO HocPhan (MaHP, TenHP, SoTinChi) VALUES ('QTKD01', N'Kinh tế vi mô', 2);
INSERT INTO HocPhan (MaHP, TenHP, SoTinChi) VALUES ('QTKD02', N'Xác suất thống kê 1', 3);
