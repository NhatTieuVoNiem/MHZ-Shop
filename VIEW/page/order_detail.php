<?php
session_start();
// Đường dẫn gốc (dùng khi include từ các trang con)
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');
require_once '../../MODEL/connect.php';
require_once '../../MODEL/Order.php';

if (!isset($_SESSION['account_id'])) {
    header("Location: login.php");
    exit();
}

$orderModel = new Order($conn);

$orderId = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

if ($orderId <= 0) {
    header("Location: orders.php");
    exit();
}

$order = $orderModel->getById($orderId);

if (!$order) {
    header("Location: orders.php");
    exit();
}

$orderItemsResult = $orderModel->getOrderItems($orderId);

$orderItems = [];

while ($row = $orderItemsResult->fetch_assoc()) {
    $orderItems[] = $row;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MHZ Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/reset.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/font.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/common.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/menu.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/header.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/footer.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/nav-menu.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/order_detail.css?v=<?= time() ?>" />
</head>

<body>
    <div class="wrapper">
        <?php
        if (isset($_SESSION['account_id'])) {
            require '../includes/nav_menu_login.php';
        } else {
            require '../includes/nav-menu.php';
        }
        ?>
        <div class="content">
            <?php require '../includes/header.php'; ?>

            <main class="body">

                <section class="order-detail">

                    <!-- Thông tin đơn hàng -->
                    <div class="order-header">
                        <div>
                            <h1>Chi tiết đơn hàng</h1>
                            <p>
                                Mã đơn hàng:
                            <h3>
                                #ORD-<?= str_pad(
                                            $order['order_id'],
                                            8,
                                            "0",
                                            STR_PAD_LEFT
                                        ) ?>
                            </h3>
                            </p>
                        </div>

                        <div class="order-status">
                            <?= htmlspecialchars($order['status']) ?>
                        </div>
                    </div>

                    <!-- Thông tin chung -->
                    <div class="order-info">

                        <div class="info-card">
                            <h3>Ngày đặt hàng</h3>
                            <p>
                                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                            </p>
                        </div>

                        <div class="info-card">
                            <h3>Tổng thanh toán</h3>
                            <p class="price">
                                <?= number_format($order['total_amount'], 0, ',', '.') ?>đ
                            </p>
                        </div>

                        <div class="info-card">
                            <h3>Số sản phẩm</h3>
                            <p>
                                <?= count($orderItems) ?>
                            </p>
                        </div>

                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="order-products">

                        <div class="section-title">
                            <h2>Sản phẩm trong đơn hàng</h2>
                        </div>

                        <?php if (!empty($orderItems)): ?>
                            <?php foreach ($orderItems as $item): ?>

                                <div class="product-item">

                                    <div class="product-left">

                                        <img
                                            src="<?= BASE_URL . htmlspecialchars($item['thumbnail_url']) ?>"
                                            alt="<?= htmlspecialchars($item['product_name']) ?>"
                                            onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                                        <div class="product-info">

                                            <h3>
                                                <a href="productsDetails.php?id=<?= $item['product_id'] ?>">
                                                    <?= htmlspecialchars($item['product_name']) ?>
                                                </a>
                                            </h3>

                                            <p>
                                                Đơn giá:
                                                <?= number_format($item['price'], 0, ',', '.') ?>đ
                                            </p>

                                            <span>
                                                Số lượng:
                                                <?= $item['quantity'] ?>
                                            </span>

                                        </div>

                                    </div>

                                    <div class="product-total">
                                        <?= number_format(
                                            $item['price'] * $item['quantity'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>đ
                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="empty-order-banner">

                                <img
                                    src="<?= BASE_URL ?>assets/images/banner/empty-order.png"
                                    alt="Không có đơn hàng"
                                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                                <div class="empty-content">
                                    <h2>Đơn hàng hiện chưa có sản phẩm</h2>

                                    <p>
                                        Có vẻ như sản phẩm trong đơn hàng đã bị xóa hoặc chưa được cập nhật.
                                    </p>

                                    <a
                                        href="<?= BASE_URL ?>page/products.php"
                                        class="btn-shop">
                                        Khám phá sản phẩm
                                    </a>
                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- Tổng kết -->
                    <div class="order-summary">

                        <div class="summary-row">
                            <span>Tạm tính</span>
                            <strong>
                                <?= number_format($order['total_amount'], 0, ',', '.') ?>đ
                            </strong>
                        </div>

                        <div class="summary-row total">
                            <span>Tổng thanh toán</span>
                            <strong>
                                <?= number_format($order['total_amount'], 0, ',', '.') ?>đ
                            </strong>
                        </div>

                    </div>

                    <div class="order-actions">
                        <a href="order_user.php" class="btn-back">
                            <i class="fa-solid fa-arrow-left"></i>
                            Quay lại đơn hàng
                        </a>
                    </div>

                </section>

            </main>

            <?php require '../includes/footer.php'; ?>
        </div>

    </div>
    <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
    <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
    <?php if (!isset($_SESSION['account_id'])): ?>
        <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
    <?php endif; ?>
</body>

</html>