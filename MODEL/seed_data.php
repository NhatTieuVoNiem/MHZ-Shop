<?php
include 'connect.php';
// Thêm dữ liệu mẫu cho bảng roles
$conn->query("INSERT INTO roles (role_name) VALUES ('admin'), ('user'), ('seller')");

// Thêm dữ liệu mẫu cho bảng genders
$conn->query("INSERT INTO genders (gender_name) VALUES ('Nam'), ('Nữ'), ('Khác')");

// Thêm dữ liệu mẫu cho bảng accounts
$conn->query("INSERT INTO accounts (username, email, password_hash, role_id) 
VALUES 
('admin', 'luongtranvymhz@gmail.com', '12345678', 1),
('NgocMHZ', 'luongtranvy@gmail.com', '12345678', 2),
('NhatTieuVoNiem', 'luongtranvy26042002@gmail.com', '12345678', 3)");
$conn->query("INSERT INTO categories (category_name, description) 
VALUES 
('WEB', 'Các sản phẩm liên quan tới website'),
('Key', 'Các sản phẩm liên quan tới bản quyền'),
('STORE', 'Các sản phẩm liên quan tới lưu trữ')");

echo "Đã thêm dữ liệu mẫu thành công.";
$conn->close();
