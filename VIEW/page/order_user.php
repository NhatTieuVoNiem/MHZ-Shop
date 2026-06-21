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

$orders = $orderModel->getOrdersByAccount(
    $_SESSION['account_id']
);
$createdDate = $_SESSION['created_at'] ?? date('Y-m-d');
$daysActive = floor(
    (time() - strtotime($createdDate)) / (60 * 60 * 24)
);
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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/order_user.css?v=<?= time() ?>" />
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
                <section class="orders-page">

                    <div class="orders-header">
                        <h1>
                            <i class="fa-solid fa-box"></i>
                            Đơn hàng của tôi
                        </h1>
                    </div>

                    <div class="orders-list">

                        <?php if ($orders->num_rows > 0): ?>

                            <?php while ($order = $orders->fetch_assoc()): ?>

                                <?php
                                $items = $orderModel->getOrderItems(
                                    $order['order_id']
                                );
                                ?>

                                <article class="order-card">

                                    <div class="order-top">

                                        <div>
                                            <h3>
                                                #ORD-<?= str_pad(
                                                            $order['order_id'],
                                                            8,
                                                            "0",
                                                            STR_PAD_LEFT
                                                        ) ?>
                                            </h3>

                                            <span>
                                                <?= date(
                                                    'd/m/Y H:i',
                                                    strtotime($order['created_at'])
                                                ) ?>
                                            </span>
                                        </div>

                                        <span class="status <?= $order['status'] ?>">
                                            <?= $order['status'] ?>
                                        </span>

                                    </div>

                                    <div class="order-products">

                                        <?php while ($item = $items->fetch_assoc()): ?>

                                            <div class="product-item">

                                                <img
                                                    src="<?= BASE_URL . htmlspecialchars($item['thumbnail_url']) ?>"
                                                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                                                <div>

                                                    <h4>
                                                        <?= htmlspecialchars($item['product_name']) ?>
                                                    </h4>

                                                    <p>
                                                        Số lượng:
                                                        <?= $item['quantity'] ?>
                                                    </p>

                                                </div>

                                            </div>

                                        <?php endwhile; ?>

                                    </div>

                                    <div class="order-bottom">

                                        <div class="total-price">
                                            Tổng tiền:
                                            <strong>
                                                <?= number_format(
                                                    $order['total_amount'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ) ?> VNĐ
                                            </strong>
                                        </div>

                                        <div class="order-actions">

                                            <a
                                                href="order_detail.php?id=<?= $order['order_id'] ?>"
                                                class="btn-detail">
                                                Chi tiết
                                            </a>

                                            <?php if ($order['status'] == 'completed'): ?>

                                                <a
                                                    href="../../CONTROLLER/controller_download_order.php?id=<?= $order['order_id'] ?>"
                                                    class="btn-download">
                                                    Tải xuống
                                                </a>

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </article>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <div class="empty-orders">

                                <i class="fa-solid fa-box-open"></i>

                                <h3>Chưa có đơn hàng nào</h3>

                                <p>Hãy mua sản phẩm để tạo đơn hàng đầu tiên.</p>

                                <a href="products.php" class="btn-shop">
                                    Mua ngay
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="account-active-card">
                        <div class="active-icon">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>

                        <div class="active-info">
                            <h3>Tài khoản hoạt động</h3>

                            <div class="active-days">
                                <?= number_format($daysActive) ?>
                                <span>ngày</span>
                            </div>

                            <p>
                                Cảm ơn bạn đã đồng hành cùng MHZ Shop.
                            </p>
                        </div>
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