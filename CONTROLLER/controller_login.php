<?php
session_start();

// Kết nối DB
require_once("../MODEL/connect.php");

// Model
require_once("../MODEL/Account.php");

// Tạo object
$accountModel = new Account($conn);

// Kiểm tra submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Lấy dữ liệu
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // =========================
    // Kiểm tra rỗng
    // =========================
    if (empty($username) || empty($password)) {

        $_SESSION["error"] = "Vui lòng nhập đầy đủ thông tin";

        header("Location: ../VIEW/page/login.php");
        exit();
    }

    // =========================
    // Tìm user theo username
    // =========================
    $user = $accountModel->findByUsername($username);

    // =========================
    // Kiểm tra tài khoản tồn tại
    // =========================
    if ($user) {

        // =========================
        // Kiểm tra mật khẩu mã hóa
        // =========================
        if (password_verify($password, $user["password_hash"])) {

            // Lưu session
            $_SESSION["account_id"] = $user["account_id"];
            $_SESSION["username"]   = $user["username"];
            $_SESSION["role_id"]    = $user["role_id"];

            // Đăng nhập thành công
            $_SESSION["success"] = "Đăng nhập thành công";

            // Chuyển trang
            switch ((int)$user["role_id"]) {
                case 1:
                    header("Location: ../VIEW/page/admin.php");
                    break;
                case 2:
                    header("Location: ../VIEW/page/seller.php");
                    break;
                default: // role_id = 3 hoặc bất kỳ role nào khác → user thường
                    header("Location: ../VIEW/page/user.php");
                    break;
            }
            exit();
        } else {

            $_SESSION["error"] = "Sai mật khẩu";

            header("Location: ../VIEW/page/login.php");
            exit();
        }
    } else {

        $_SESSION["error"] = "Tên tài khoản không tồn tại";

        header("Location: ../VIEW/page/login.php");
        exit();
    }
}
