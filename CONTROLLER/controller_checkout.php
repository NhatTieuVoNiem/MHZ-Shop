<?php
session_start();

require_once("../MODEL/connect.php");

require_once("../MODEL/Cart.php");
require_once("../MODEL/CartItem.php");

require_once("../MODEL/Order.php");
require_once("../MODEL/OrderItem.php");

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['account_id'])) {

    header("Location: ../VIEW/page/login.php");
    exit;
}

// Lấy ID tài khoản từ session
$account_id = $_SESSION['account_id'];

// Lấy thông tin khách hàng từ form thanh toán
$fullname = trim($_POST['fullname'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// Kiểm tra dữ liệu bắt buộc
if (
    empty($fullname) ||
    empty($phone)
) {
    die("Vui lòng nhập đầy đủ thông tin.");
}

// Khởi tạo các model cần sử dụng
$cartModel = new Cart($conn);
$cartItemModel = new CartItem($conn);

$orderModel = new Order($conn);
$orderItemModel = new OrderItem($conn);

// Lấy giỏ hàng của người dùng
$cart = $cartModel->getByAccountId(
    $account_id
);

// Kiểm tra giỏ hàng tồn tại
if (!$cart) {

    die("Không tìm thấy giỏ hàng.");
}

// Lấy danh sách sản phẩm trong giỏ hàng
$result = $cartItemModel->getByCartId(
    $cart['cart_id']
);

// Kiểm tra giỏ hàng có sản phẩm hay không
if ($result->num_rows == 0) {

    die("Giỏ hàng đang trống.");
}

// Tính tổng tiền đơn hàng và lưu danh sách sản phẩm
$total_amount = 0;
$items = [];

while ($row = $result->fetch_assoc()) {

    $total_amount +=
        $row['price'] * $row['quantity'];

    $items[] = $row;
}

// Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
$conn->begin_transaction();

try {

    // Tạo đơn hàng mới và lấy ID đơn hàng vừa tạo
    $order_id =
        $orderModel->createAndGetId(
            $account_id,
            $total_amount,
            'pending'
        );

    // Thêm từng sản phẩm vào bảng chi tiết đơn hàng
    foreach ($items as $item) {

        $orderItemModel->create(
            $order_id,
            $item['product_id'],
            $item['quantity']
        );
    }

    // Xóa toàn bộ sản phẩm khỏi giỏ hàng sau khi đặt hàng thành công
    $stmt = $conn->prepare("
        DELETE FROM cart_items
        WHERE cart_id = ?
    ");

    $stmt->bind_param(
        "i",
        $cart['cart_id']
    );

    $stmt->execute();

    // Xác nhận transaction
    $conn->commit();

    // Thông báo đặt hàng thành công
    $_SESSION['success'] =
        "Đặt hàng thành công!";

    // Chuyển hướng đến trang đơn hàng của người dùng
    header(
        "Location: ../VIEW/page/order_user.php"
    );
    exit;
} catch (Exception $e) {

    // Hoàn tác dữ liệu nếu có lỗi xảy ra
    $conn->rollback();

    die("Lỗi thanh toán: "
        . $e->getMessage());
}
