<?php
session_start();

require_once("../MODEL/connect.php");
require_once("../MODEL/CartItem.php");

if (!isset($_SESSION['account_id'])) {
    header("Location: ../VIEW/page/login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$cartItemModel = new CartItem($conn);

$cartItemModel->delete($id);

header("Location: ../VIEW/page/cart.php");
exit;