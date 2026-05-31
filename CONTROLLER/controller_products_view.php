<?php

ob_start();

session_start();
require_once("../MODEL/connect.php");
require_once("../MODEL/ProductView.php");

$action = $_POST['action'] ?? '';

if ($action === 'trackView') {
    // Chưa đăng nhập thì gán account_id 
   $account_id = isset($_SESSION['account_id']) ? (int)$_SESSION['account_id'] : null;
    $product_id  = isset($_POST['product_id'])    ? (int)$_POST['product_id']    : 0;
    // Loại ký tự xuống dòng trong URL redirect để tránh lỗi header
    $redirect_url = trim(str_replace(["\r", "\n", "\t"], '', $_POST['redirect_url'] ?? '/'));

    if ($product_id > 0) {
        $viewModel = new ProductView($conn);
        $viewModel->create($account_id, $product_id);
    }

    ob_end_clean();
    header("Location: " . $redirect_url);
    exit;
}

ob_end_clean();
