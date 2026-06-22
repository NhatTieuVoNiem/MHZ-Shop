<?php
session_start();

if (!isset($_SESSION['account_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role_id'] != 3) {
    header("Location: login.php");
    exit();
}

define('BASE_PATH', __DIR__);
define('BASE_URL', '../');

require_once '../../MODEL/connect.php';
require_once '../../MODEL/Order.php';
require_once '../../MODEL/Product.php';
require_once '../../MODEL/Account.php';

$orderModel   = new Order($conn);
$productModel = new Product($conn);
$accountModel = new Account($conn);

$accountId = $_SESSION['account_id'];
$totalProducts  = $productModel->countSellerProducts($accountId);

$totalOrders    = $orderModel->countSellerOrders($accountId);

$totalRevenue   = $orderModel->getSellerRevenue($accountId);

$totalCustomers = $orderModel->countSellerCustomers($accountId);

$recentOrders   = $orderModel->getRecentSellerOrders($accountId);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MHZ Shop - User Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>style/reset.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/font.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/menu.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/header.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/footer.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/nav-menu.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>style/seller.css?v=<?= time() ?>">
</head>

<body>

    <div class="wrapper">

        <?php require '../includes/nav_menu_login.php'; ?>

        <div class="content">

            <?php require '../includes/header.php'; ?>

            <main class="body">

                <!-- Banner -->
                <section class="seller-hero">
                    <div class="hero-content">
                        <h1>Chào mừng trở lại 👋</h1>
                        <p>
                            Quản lý sản phẩm, theo dõi đơn hàng và phát triển cửa hàng của bạn trên MHZ Shop.
                        </p>

                        <div class="hero-actions">
                            <a href="product_manager.php" class="btn-primary">
                                <i class="fa-solid fa-plus"></i>
                                Đăng sản phẩm
                            </a>

                            <a href="revenue.php" class="btn-outline">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                Doanh thu
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Thống kê -->
                <section class="dashboard-stats">

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fa-solid fa-cube"></i>
                        </div>

                        <div class="stat-info">
                            <h3><?= number_format($totalProducts) ?></h3>
                            <span>Sản phẩm</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>

                        <div class="stat-info">
                            <h3><?= number_format($totalOrders) ?></h3>
                            <span>Đơn hàng</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>

                        <div class="stat-info">
                            <h3><?= number_format($totalRevenue, 0, ',', '.') ?>đ</h3>
                            <span>Doanh thu</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>

                        <div class="stat-info">
                            <h3><?= number_format($totalCustomers) ?></h3>
                            <span>Khách hàng</span>
                        </div>
                    </div>

                </section>

                <!-- Chức năng nhanh -->
                <section class="quick-actions">

                    <h2>Chức năng nhanh</h2>

                    <div class="action-grid">

                        <a href="product_manager.php" class="action-card">
                            <i class="fa-solid fa-plus"></i>
                            <span>Đăng sản phẩm</span>
                        </a>

                        <a href="product_manager.php" class="action-card">
                            <i class="fa-solid fa-box-open"></i>
                            <span>Sản phẩm</span>
                        </a>

                        <a href="orders.php" class="action-card">
                            <i class="fa-solid fa-receipt"></i>
                            <span>Đơn hàng</span>
                        </a>

                        <a href="profile.php" class="action-card">
                            <i class="fa-solid fa-user"></i>
                            <span>Hồ sơ</span>
                        </a>

                    </div>

                </section>

                <!-- Hoạt động gần đây -->
                <section class="recent-section">

                    <div class="section-header">
                        <h2>Đơn hàng gần đây</h2>
                        <a href="seller_orders.php">Xem tất cả</a>
                    </div>

                    <div class="recent-orders">

                        <?php if ($recentOrders->num_rows > 0): ?>

                            <?php while ($order = $recentOrders->fetch_assoc()): ?>

                                <div class="order-item">

                                    <div>
                                        <h4>
                                            <?= htmlspecialchars($order['product_name']) ?>
                                        </h4>

                                        <span>
                                            <?= htmlspecialchars($order['username']) ?>
                                        </span>
                                    </div>

                                    <strong>
                                        <?= number_format(
                                            $order['price'] * $order['quantity'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>đ
                                    </strong>

                                </div>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <div class="empty-order">
                                <i class="fa-solid fa-box-open"></i>
                                <p>Chưa có đơn hàng nào.</p>
                            </div>

                        <?php endif; ?>

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
    <script src="<?= BASE_URL ?>js/clickCheckOut.js?v=<?= time() ?>"></script>
</body>

</html>