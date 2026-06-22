<?php
session_start();

// Xóa toàn bộ dữ liệu session
$_SESSION = [];

// Hủy session
session_destroy();

// Chuyển về trang đăng nhập
header("Location: login.php");
exit();
