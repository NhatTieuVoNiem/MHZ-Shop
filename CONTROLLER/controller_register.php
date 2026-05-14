<?php
session_start();

// Kết nối database
require_once "../MODEL/connect.php";

// Model Account
require_once "../MODEL/Account.php";

// Khởi tạo model
$accountModel = new Account($conn);

// Kiểm tra submit
if (isset($_POST['btn_register'])) {

    // Lấy dữ liệu
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Kiểm tra dữ liệu rỗng
    if (
        empty($username) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Kiểm tra xác nhận mật khẩu
    if ($password !== $confirm_password) {

        $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Kiểm tra email tồn tại
    $checkEmail = $accountModel->findByEmail($email);

    if ($checkEmail) {

        $_SESSION['error'] = "Email đã tồn tại!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Kiểm tra username tồn tại
    $checkUsername = $accountModel->findByUsername($username);

    if ($checkUsername) {

        $_SESSION['error'] = "Tên người dùng đã tồn tại!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Mã hóa mật khẩu
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // role user mặc định = 2
    $role_id = 2;

    // Tạo tài khoản
    $result = $accountModel->create(
        $username,
        $email,
        $password_hash,
        $role_id
    );

    // Kiểm tra kết quả
    if ($result) {

        $_SESSION['success'] = "Đăng ký thành công!";
        header("Location: ../VIEW/page/user.php");
        exit();

    } else {

        $_SESSION['error'] = "Đăng ký thất bại!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }
}
?>