<?php
$servername = "localhost";   // hoặc IP của máy chủ MySQL
$username   = "root";        // thay bằng username MySQL của bạn
$password   = "";            // thay bằng mật khẩu MySQL của bạn
$dbname     = "mhz_shop";    // tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
} else {
    echo "Kết nối thành công tới cơ sở dữ liệu mhz_shop!";
}
?>
