<?php
session_start();

if (
    !isset($_SESSION['account_id']) ||
    $_SESSION['role_id'] != 1
) {
    header("Location: login.php");
    exit();
}

require_once("../../MODEL/connect.php");
require_once("../../CONTROLLER/controller_product_admin.php");

$controller = new controller_product($conn);

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($id > 0) {
        $controller->deleteProduct($id);
    }
}

header("Location: products-admin.php");
exit();