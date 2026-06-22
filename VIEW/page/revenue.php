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

$totalRevenue  = $orderModel->getSellerRevenue($accountId);
$totalOrders   = $orderModel->countSellerOrders($accountId);
$totalProducts = $productModel->countSellerProducts($accountId);
$totalCustomers = $orderModel->countSellerCustomers($accountId);
$totalSold = $orderModel->countSellerSoldProducts($accountId);
$topProducts = $productModel->getSellerTopProducts($accountId);
$recentOrders =
    $orderModel->getRecentSellerOrders(
        $accountId,
        10
    );
$months = array_fill(1, 12, 0);
$monthlyRevenue =
    $orderModel->getSellerRevenueByMonth(
        $accountId
    );
while ($row = $monthlyRevenue->fetch_assoc()) {
    $months[$row['month_num']]
        = $row['revenue'];
}
$productStats = $productModel->getSellerProductSaleStats($accountId);

$totalProducts = $productStats['total_products'] ?? 0;
$soldProducts  = $productStats['sold_products'] ?? 0;
$unsoldProducts = $totalProducts - $soldProducts;

$soldPercent = $totalProducts > 0
    ? round(($soldProducts / $totalProducts) * 100)
    : 0;
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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/revenue.css?v=<?= time() ?>">
</head>

<body>

    <div class="wrapper">

        <?php require '../includes/nav_menu_login.php'; ?>

        <div class="content">

            <?php require '../includes/header.php'; ?>

            <main class="body">

                <!-- Thống kê -->
                <section class="dashboard-cards">

                    <div class="dashboard-card revenue">
                        <i class="fa-solid fa-coins"></i>
                        <div>
                            <h3>Tổng doanh thu</h3>
                            <p><?= number_format($totalRevenue, 0, ',', '.') ?>đ</p>
                        </div>
                    </div>

                    <div class="dashboard-card orders">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <div>
                            <h3>Đơn hàng</h3>
                            <p><?= $totalOrders ?></p>
                        </div>
                    </div>

                    <div class="dashboard-card products">
                        <i class="fa-solid fa-box"></i>
                        <div>
                            <h3>Sản phẩm đã bán</h3>
                            <p><?= $totalProducts ?></p>
                        </div>
                    </div>

                    <div class="dashboard-card customers">
                        <i class="fa-solid fa-users"></i>
                        <div>
                            <h3>Khách hàng</h3>
                            <p><?= $totalCustomers ?></p>
                        </div>
                    </div>

                </section>

                <section class="chart-section">

                    <div class="chart-box">

                        <!-- Biểu đồ cột -->
                        <div class="chart-card">

                            <div class="section-header">
                                <h2>
                                    <i class="fa-solid fa-chart-column"></i>
                                    Doanh thu 6 tháng gần nhất
                                </h2>
                            </div>

                            <div class="chart-container">

                                <?php
                                $maxRevenue = max($months);

                                for ($i = 1; $i <= 6; $i++) :

                                    $height =
                                        $maxRevenue > 0
                                        ? ($months[$i] / $maxRevenue) * 180
                                        : 10;
                                ?>

                                    <div class="chart-item">

                                        <div
                                            class="chart-bar"
                                            style="height:<?= $height ?>px">

                                            <span>
                                                <?= round($months[$i] / 1000000, 1) ?>M
                                            </span>

                                        </div>

                                        <p>T<?= $i ?></p>

                                    </div>

                                <?php endfor; ?>

                            </div>

                        </div>

                        <!-- Biểu đồ tròn -->
                        <div class="chart-card">

                            <div class="section-header">
                                <h2>
                                    <i class="fa-solid fa-chart-pie"></i>
                                    Tỷ lệ sản phẩm đã bán
                                </h2>
                            </div>

                            <div class="pie-chart-wrapper">
                                <?php
                                $pieStyle = "background: conic-gradient(
    #6c5ce7 0% {$soldPercent}%,
    #2d3436 {$soldPercent}% 100%
)";
                                ?>
                                <div class="pie-chart" style="<?= $pieStyle ?>">
                                </div>

                                <div class="pie-center">
                                    <?= $soldPercent ?>%
                                </div>

                                <div class="pie-info">

                                    <div>
                                        <span class="dot sold"></span>
                                        Đã bán:
                                        <b><?= $soldProducts ?></b>
                                    </div>

                                    <div>
                                        <span class="dot unsold"></span>
                                        Chưa bán:
                                        <b><?= $unsoldProducts ?></b>
                                    </div>

                                    <div>
                                        Tổng:
                                        <b><?= $totalProducts ?></b>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                <!-- Top sản phẩm -->
                <section class="table-section">

                    <div class="section-header">
                        <h2>
                            <i class="fa-solid fa-fire"></i>
                            Sản phẩm bán chạy
                        </h2>
                    </div>

                    <table class="dashboard-table">

                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đã bán</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($row = $topProducts->fetch_assoc()): ?>

                                <tr>
                                    <td><?= htmlspecialchars($row['product_name']) ?></td>

                                    <td><?= $row['total_sold'] ?></td>

                                    <td>
                                        <?= number_format($row['revenue'], 0, ',', '.') ?>đ
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </section>

                <!-- Đơn hàng gần đây -->
                <section class="table-section">

                    <div class="section-header">
                        <h2>
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Đơn hàng gần đây
                        </h2>
                    </div>

                    <table class="dashboard-table">

                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Ngày mua</th>
                                <th>Tổng tiền</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($order = $recentOrders->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($order['product_name']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($order['username']) ?>
                                    </td>

                                    <td>
                                        <?= date(
                                            'd/m/Y',
                                            strtotime($order['created_at'])
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= number_format(
                                            $order['price'] * $order['quantity'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>đ
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>
                    </table>

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