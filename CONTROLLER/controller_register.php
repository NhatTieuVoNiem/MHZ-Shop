<?php
session_start();

require_once "../MODEL/connect.php";
require_once "../MODEL/Account.php";

$accountModel = new Account($conn);

if (isset($_POST['btn_register'])) {

    $username         = trim($_POST['username'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // ── 1. Kiểm tra rỗng ──────────────────────────────────────────
    $emptyFields = [];
    if (empty($username))         $emptyFields[] = "Tên người dùng";
    if (empty($email))            $emptyFields[] = "Email";
    if (empty($password))         $emptyFields[] = "Mật khẩu";
    if (empty($confirm_password)) $emptyFields[] = "Xác nhận mật khẩu";

    if (!empty($emptyFields)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ: " . implode(", ", $emptyFields) . "!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // ── 2. Kiểm tra định dạng email ───────────────────────────────
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email không đúng định dạng!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // ── 3. Kiểm tra độ dài mật khẩu ──────────────────────────────
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Mật khẩu phải có ít nhất 6 ký tự!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // ── 4. Kiểm tra mật khẩu khớp ────────────────────────────────
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Mật khẩu xác nhận không khớp – vui lòng nhập lại cả hai ô mật khẩu!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // ── 5. Kiểm tra email đã tồn tại ─────────────────────────────
    if ($accountModel->findByEmail($email)) {
        $_SESSION['error'] = "Email \"$email\" đã được đăng ký. Vui lòng dùng email khác hoặc đăng nhập!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // ── 6. Kiểm tra username đã tồn tại ──────────────────────────
    if ($accountModel->findByUsername($username)) {
        $_SESSION['error'] = "Tên người dùng \"$username\" đã tồn tại. Vui lòng chọn tên khác!";
        header("Location: ../VIEW/page/register.php");
        exit();
    }

    // ── 7. Tạo tài khoản ─────────────────────────────────────────
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $role_id       = 2; // mặc định: user

    try {
        $result = $accountModel->create($username, $email, $password_hash, $role_id);

        if ($result) {
            $_SESSION['success'] = "Đăng ký thành công! Chào mừng $username, đang chuyển đến trang của bạn…";
            header("Location: ../VIEW/page/user.php");
            exit();
        } else {
            $_SESSION['error'] = "Đăng ký thất bại – không thể lưu tài khoản vào cơ sở dữ liệu. Vui lòng thử lại!";
            header("Location: ../VIEW/page/register.php");
            exit();
        }

    } catch (Exception $e) {
        // Bắt lỗi cụ thể từ DB (nếu model ném exception)
        $_SESSION['error'] = "Đăng ký thất bại – lỗi hệ thống: " . $e->getMessage();
        header("Location: ../VIEW/page/register.php");
        exit();
    }
}
?>