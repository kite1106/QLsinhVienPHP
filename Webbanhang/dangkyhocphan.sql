-- Create database if not exists
CREATE DATABASE IF NOT EXISTS DangKyHocPhan;
USE DangKyHocPhan;

-- Tạo bảng NganhHoc
CREATE TABLE IF NOT EXISTS NganhHoc (
    MaNganh VARCHAR(10) PRIMARY KEY,
    TenNganh VARCHAR(100) NOT NULL,
    MoTa TEXT
);

-- Tạo bảng SinhVien
CREATE TABLE IF NOT EXISTS SinhVien (
    MaSV VARCHAR(10) PRIMARY KEY,
    HoTen VARCHAR(50) NOT NULL,
    GioiTinh VARCHAR(10),
    NgaySinh DATE,
    MaNganh VARCHAR(10),
    MatKhau VARCHAR(32) NOT NULL,  -- MD5 hash
    Hinh VARCHAR(255),
    FOREIGN KEY (MaNganh) REFERENCES NganhHoc(MaNganh)
);

-- Tạo bảng HocPhan
CREATE TABLE IF NOT EXISTS HocPhan (
    MaHP VARCHAR(10) PRIMARY KEY,
    TenHP VARCHAR(100) NOT NULL,
    SoTC INT NOT NULL,
    SucChua INT NOT NULL,
    MoTa TEXT
);

-- Tạo bảng DangKy
CREATE TABLE IF NOT EXISTS DangKy (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    MaSV VARCHAR(10),
    MaHP VARCHAR(10),
    NgayDK DATETIME DEFAULT CURRENT_TIMESTAMP,
    TrangThai VARCHAR(20) DEFAULT 'Chờ duyệt',  -- 'Chờ duyệt', 'Đã duyệt', 'Đã hủy'
    FOREIGN KEY (MaSV) REFERENCES SinhVien(MaSV),
    FOREIGN KEY (MaHP) REFERENCES HocPhan(MaHP),
    UNIQUE KEY unique_dangky (MaSV, MaHP)  -- Một sinh viên không thể đăng ký cùng một học phần 2 lần
);

-- Thêm dữ liệu mẫu cho bảng NganhHoc
INSERT INTO NganhHoc (MaNganh, TenNganh, MoTa) VALUES 
('CNTT', 'Công nghệ thông tin', 'Ngành học về công nghệ thông tin và lập trình'),
('KTPM', 'Kỹ thuật phần mềm', 'Chuyên về phát triển phần mềm và quy trình phần mềm'),
('KHMT', 'Khoa học máy tính', 'Nghiên cứu về khoa học máy tính và trí tuệ nhân tạo'),
('HTTT', 'Hệ thống thông tin', 'Chuyên về hệ thống thông tin quản lý và cơ sở dữ liệu'),
('MMT', 'Mạng máy tính', 'Chuyên về mạng máy tính và an ninh mạng');

-- Thêm dữ liệu mẫu cho bảng HocPhan
INSERT INTO HocPhan (MaHP, TenHP, SoTC, SucChua, MoTa) VALUES 
('LTCB', 'Lập trình cơ bản', 3, 40, 'Học phần cơ bản về lập trình'),
('CSDL', 'Cơ sở dữ liệu', 3, 35, 'Học phần về cơ sở dữ liệu'),
('CTDL', 'Cấu trúc dữ liệu', 4, 30, 'Học phần về cấu trúc dữ liệu và giải thuật'),
('MMT', 'Mạng máy tính', 3, 35, 'Học phần về mạng máy tính'),
('HTCN', 'Hệ thống chuyên nghiệp', 4, 30, 'Học phần về phát triển hệ thống');
