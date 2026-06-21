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
                <a href="create_product.php" class="btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Đăng sản phẩm
                </a>

                <a href="seller_products.php" class="btn-outline">
                    <i class="fa-solid fa-box"></i>
                    Kho sản phẩm
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
                <h3>24</h3>
                <span>Sản phẩm</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>

            <div class="stat-info">
                <h3>156</h3>
                <span>Đơn hàng</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>

            <div class="stat-info">
                <h3>12.5M</h3>
                <span>Doanh thu</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="stat-info">
                <h3>89</h3>
                <span>Khách hàng</span>
            </div>
        </div>

    </section>

    <!-- Chức năng nhanh -->
    <section class="quick-actions">

        <h2>Chức năng nhanh</h2>

        <div class="action-grid">

            <a href="create_product.php" class="action-card">
                <i class="fa-solid fa-plus"></i>
                <span>Đăng sản phẩm</span>
            </a>

            <a href="seller_products.php" class="action-card">
                <i class="fa-solid fa-box-open"></i>
                <span>Sản phẩm</span>
            </a>

            <a href="seller_orders.php" class="action-card">
                <i class="fa-solid fa-receipt"></i>
                <span>Đơn hàng</span>
            </a>

            <a href="seller_profile.php" class="action-card">
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

            <div class="order-item">
                <div>
                    <h4>Website Bán Hàng</h4>
                    <span>Nguyễn Văn A</span>
                </div>

                <strong>500.000đ</strong>
            </div>

            <div class="order-item">
                <div>
                    <h4>Source Code Shop</h4>
                    <span>Trần Văn B</span>
                </div>

                <strong>1.200.000đ</strong>
            </div>

            <div class="order-item">
                <div>
                    <h4>Template Landing Page</h4>
                    <span>Lê Văn C</span>
                </div>

                <strong>350.000đ</strong>
            </div>

        </div>

    </section>

</main>

      <?php require '../includes/footer.php'; ?>

    </div>

  </div>

</body>

</html>