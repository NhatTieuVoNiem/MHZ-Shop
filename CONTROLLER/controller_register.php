<?php
session_start();

require_once "../MODEL/connect.php";
require_once "../MODEL/Account.php";

// Khởi tạo Account Model
$accountModel = new Account($conn);

// Kiểm tra người dùng gửi form đăng ký
if (isset($_POST['btn_register'])) {

    // Lấy dữ liệu từ form đăng ký
    $username         = trim($_POST['username'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Kiểm tra các trường bắt buộc
    $emptyFields = [];

    if (empty($username))
        $emptyFields[] = "Tên người dùng";

    if (empty($email))
        $emptyFields[] = "Email";

    if (empty($password))
        $emptyFields[] = "Mật khẩu";

    if (empty($confirm_password))
        $emptyFields[] = "Xác nhận mật khẩu";

    // Hiển thị lỗi nếu có trường bị bỏ trống
    if (!empty($emptyFields)) {

        $_SESSION['error'] =
            "Vui lòng nhập đầy đủ: "
            . implode(", ", $emptyFields)
            . "!";

        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Kiểm tra định dạng Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION['error'] =
            "Email không đúng định dạng!";

        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Kiểm tra độ dài mật khẩu
    if (strlen($password) < 6) {

        $_SESSION['error'] =
            "Mật khẩu phải có ít nhất 6 ký tự!";

        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Kiểm tra mật khẩu xác nhận
    if ($password !== $confirm_password) {

        $_SESSION['error'] =
            "Mật khẩu xác nhận không khớp – vui lòng nhập lại cả hai ô mật khẩu!";

        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Kiểm tra Email đã tồn tại hay chưa
    if ($accountModel->findByEmail($email)) {

        $_SESSION['error'] =
            "Email \"$email\" đã được đăng ký. Vui lòng dùng email khác hoặc đăng nhập!";

        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Kiểm tra Username đã tồn tại hay chưa
    if ($accountModel->findByUsername($username)) {

        $_SESSION['error'] =
            "Tên người dùng \"$username\" đã tồn tại. Vui lòng chọn tên khác!";

        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
    $password_hash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // Gán quyền mặc định là User
    $role_id = 2;

    try {

        // Tạo tài khoản mới
        $result = $accountModel->create(
            $username,
            $email,
            $password_hash,
            $role_id
        );

        // Kiểm tra kết quả đăng ký
        if ($result) {

            $_SESSION['success'] =
                "Đăng ký thành công! Chào mừng $username, đang chuyển đến trang của bạn…";

            header("Location: ../VIEW/page/user.php");
            exit();
        } else {

            $_SESSION['error'] =
                "Đăng ký thất bại – không thể lưu tài khoản vào cơ sở dữ liệu. Vui lòng thử lại!";

            header("Location: ../VIEW/page/register.php");
            exit();
        }
    } catch (Exception $e) {

        // Bắt lỗi phát sinh từ Database hoặc Model
        $_SESSION['error'] =
            "Đăng ký thất bại – lỗi hệ thống: "
            . $e->getMessage();

        header("Location: ../VIEW/page/register.php");
        exit();
    }
}
