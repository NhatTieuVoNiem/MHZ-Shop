<?php
session_start();

require_once("../MODEL/connect.php");

require_once("../MODEL/Cart.php");
require_once("../MODEL/CartItem.php");

require_once("../MODEL/Order.php");
require_once("../MODEL/OrderItem.php");

if (!isset($_SESSION['account_id'])) {

    header("Location: ../VIEW/page/login.php");
    exit;
}

$account_id = $_SESSION['account_id'];

$fullname = trim($_POST['fullname'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (
    empty($fullname) ||
    empty($phone)
) {
    die("Vui lòng nhập đầy đủ thông tin.");
}

$cartModel = new Cart($conn);
$cartItemModel = new CartItem($conn);

$orderModel = new Order($conn);
$orderItemModel = new OrderItem($conn);

$cart = $cartModel->getByAccountId(
    $account_id
);

if (!$cart) {

    die("Không tìm thấy giỏ hàng.");
}

$result = $cartItemModel->getByCartId(
    $cart['cart_id']
);

if ($result->num_rows == 0) {

    die("Giỏ hàng đang trống.");
}

$total_amount = 0;
$items = [];

while ($row = $result->fetch_assoc()) {

    $total_amount +=
        $row['price'] * $row['quantity'];

    $items[] = $row;
}

$conn->begin_transaction();

try {

   $order_id =
    $orderModel->createAndGetId(
        $account_id,
        $total_amount,
        'pending'
    );

    foreach ($items as $item) {

    $orderItemModel->create(
    $order_id,
    $item['product_id'],
    $item['quantity']
);
    }

    $stmt = $conn->prepare("
        DELETE FROM cart_items
        WHERE cart_id = ?
    ");

    $stmt->bind_param(
        "i",
        $cart['cart_id']
    );

    $stmt->execute();

    $conn->commit();

    $_SESSION['success'] =
        "Đặt hàng thành công!";

    header(
        "Location: ../VIEW/page/order_user.php"
    );
    exit;

} catch (Exception $e) {

    $conn->rollback();

    die(
        "Lỗi thanh toán: "
        . $e->getMessage()
    );
}