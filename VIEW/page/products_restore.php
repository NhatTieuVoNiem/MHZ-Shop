<?php
session_start();

require_once("../../MODEL/connect.php");

if (!isset($_GET['id'])) {
    header("Location: products-admin.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("
    UPDATE products
    SET status = 1
    WHERE product_id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: products-admin.php");
exit;