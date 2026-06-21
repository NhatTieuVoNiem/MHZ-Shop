<?php
session_start();

require_once("../MODEL/connect.php");
require_once("../MODEL/Cart.php");
require_once("../MODEL/CartItem.php");

if (!isset($_SESSION['account_id'])) {

    header("Location: ../VIEW/page/login.php");
    exit;
}

$account_id = $_SESSION['account_id'];

$product_id = isset($_POST['product_id'])
    ? (int)$_POST['product_id']
    : 0;

if ($product_id <= 0) {
    die("Sản phẩm không hợp lệ");
}

$cartModel = new Cart($conn);
$cartItemModel = new CartItem($conn);

$cart = $cartModel->getByAccountId($account_id);

if (!$cart) {

    $cartModel->create($account_id);

    $cart = $cartModel->getByAccountId($account_id);
}

$cart_id = $cart['cart_id'];

$item = $cartItemModel->getByCartAndProduct(
    $cart_id,
    $product_id
);

if ($item) {

    $cartItemModel->increaseQuantity(
        $item['cart_item_id']
    );

} else {

    $cartItemModel->create(
        $cart_id,
        $product_id,
        1
    );
}

header("Location: ../VIEW/page/cart.php");
exit;