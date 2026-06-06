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
require_once("../../CONTROLLER/controller_account.php");

$controller = new controller_account($conn);

if(isset($_GET['id']))
{
    $id = (int)$_GET['id'];

    // Không cho admin tự xóa chính mình
    if($id != $_SESSION['account_id'])
    {
        $controller->deleteAccount($id);
    }
}

header("Location: accounts.php");
exit();