<?php

ob_start();

session_start();

require_once("../MODEL/connect.php");
require_once("../MODEL/ProductView.php");

// Lấy hành động được gửi từ form
$action = $_POST['action'] ?? '';

// Xử lý ghi nhận lượt xem sản phẩm
if ($action === 'trackView') {

    // Lấy ID tài khoản nếu đã đăng nhập
    // Nếu chưa đăng nhập thì gán giá trị null
    $account_id = isset($_SESSION['account_id'])
        ? (int)$_SESSION['account_id']
        : null;

    // Lấy ID sản phẩm cần ghi nhận lượt xem
    $product_id = isset($_POST['product_id'])
        ? (int)$_POST['product_id']
        : 0;

    // Lấy URL chuyển hướng sau khi ghi nhận lượt xem
    // Loại bỏ ký tự xuống dòng và khoảng trắng thừa
    // để tránh lỗi hoặc lỗ hổng Header Injection
    $redirect_url = trim(
        str_replace(
            ["\r", "\n", "\t"],
            '',
            $_POST['redirect_url'] ?? '/'
        )
    );

    // Kiểm tra sản phẩm hợp lệ
    if ($product_id > 0) {

        // Khởi tạo ProductView Model
        $viewModel = new ProductView($conn);

        // Lưu lượt xem sản phẩm vào cơ sở dữ liệu
        $viewModel->create(
            $account_id,
            $product_id
        );
    }

    // Xóa toàn bộ dữ liệu trong bộ đệm output
    ob_end_clean();

    // Chuyển hướng đến trang đích
    header("Location: " . $redirect_url);
    exit;
}

// Kết thúc bộ đệm nếu không có hành động hợp lệ
ob_end_clean();
