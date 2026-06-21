<?php
session_start();

require_once("../MODEL/connect.php");
require_once("../MODEL/ProductLike.php");

if (!isset($_SESSION['account_id'])) {

    header("Location: ../VIEW/page/login.php");
    exit;
}

$account_id = $_SESSION['account_id'];

$product_id = isset($_POST['product_id'])
    ? (int)$_POST['product_id']
    : 0;

$likeModel = new ProductLike($conn);

$liked = $likeModel->checkLike(
    $account_id,
    $product_id
);

if ($liked) {

    $likeModel->removeLike(
        $account_id,
        $product_id
    );

} else {

    $likeModel->create(
        $account_id,
        $product_id
    );
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;