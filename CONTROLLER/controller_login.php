<?php
session_start();

// Kết nối cơ sở dữ liệu
require_once("../MODEL/connect.php");

// Nạp model tài khoản
require_once("../MODEL/Account.php");

// Khởi tạo đối tượng Account
$accountModel = new Account($conn);

// Kiểm tra form được gửi bằng phương thức POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Lấy dữ liệu đăng nhập từ form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Kiểm tra dữ liệu bắt buộc
    if (empty($username) || empty($password)) {

        $_SESSION["error"] = "Vui lòng nhập đầy đủ thông tin";

        header("Location: ../VIEW/page/login.php");
        exit();
    }

    // Tìm tài khoản theo tên đăng nhập
    $user = $accountModel->findByUsername($username);

    // Kiểm tra tài khoản có tồn tại hay không
    if ($user) {

        // Kiểm tra mật khẩu đã mã hóa
        if (password_verify($password, $user["password_hash"])) {

            // Lưu thông tin người dùng vào Session
            $_SESSION["account_id"] = $user["account_id"];
            $_SESSION["username"]   = $user["username"];
            $_SESSION["role_id"]    = $user["role_id"];
            $_SESSION["avatar_url"] = $user["avatar_url"];

            // Thông báo đăng nhập thành công
            $_SESSION["success"] = "Đăng nhập thành công";

            // Điều hướng theo vai trò người dùng
            switch ((int)$user["role_id"]) {

                // Quản trị viên
                case 1:
                    header("Location: ../VIEW/page/admin.php");
                    break;

                // Người dùng
                case 2:
                    header("Location: ../VIEW/page/user.php");
                    break;

                // Người bán hàng
                case 3:
                    header("Location: ../VIEW/page/seller.php");
                    break;
            }

            exit();
        } else {

            // Thông báo sai mật khẩu
            $_SESSION["error"] = "Sai mật khẩu";

            header("Location: ../VIEW/page/login.php");
            exit();
        }
    } else {

        // Thông báo tài khoản không tồn tại
        $_SESSION["error"] = "Tên tài khoản không tồn tại";

        header("Location: ../VIEW/page/login.php");
        exit();
    }
}
