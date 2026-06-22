<?php

define('BASE_PATH', __DIR__);
define('BASE_URL', '../');

session_start();

if (
    !isset($_SESSION['account_id']) ||
    $_SESSION['role_id'] != 1
) {
    header("Location: login.php");
    exit();
}
$totalOrders = 0;
$completedOrders = 0;
$processingOrders = 0;
$cancelledOrders = 0;
$orders = null;
require_once("../../CONTROLLER/controller_order_detail.php");
/** @var array $order */
/** @var string $statusText */
/** @var mysqli_result $items */

?>

<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý đơn hàng | MHZ Admin</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet"
        href="<?= BASE_URL ?>style/reset.css?v=<?= time() ?>">

    <link rel="stylesheet"
        href="<?= BASE_URL ?>style/font.css?v=<?= time() ?>">

    <link rel="stylesheet"
        href="<?= BASE_URL ?>style/common.css?v=<?= time() ?>">

    <link rel="stylesheet"
        href="<?= BASE_URL ?>style/footer.css?v=<?= time() ?>">

    <link rel="stylesheet"
        href="<?= BASE_URL ?>style/order-detail.css?v=<?= time() ?>">

</head>

<body>

    <div class="wrapper">

        <!-- HEADER -->

        <div class="admin-header">

            <a href="admin.php" class="logo">
                <h2>MHZ Admin</h2>
            </a>

            <nav class="admin-nav">

                <a href="admin.php">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>

                <a href="accounts.php">
                    <i class="fas fa-users"></i>
                    Tài khoản
                </a>

                <a href="products-admin.php">
                    <i class="fas fa-gamepad"></i>
                    Sản phẩm
                </a>

                <a href="orders.php" class="active">
                    <i class="fas fa-shopping-cart"></i>
                    Đơn hàng
                </a>

                <a href="reports.php">
                    <i class="fas fa-chart-line"></i>
                    Báo cáo
                </a>

            </nav>

            <div class="admin-user">

                <?php if (!empty($_SESSION['avatar_url'])): ?>

                    <img
                        src="<?= BASE_URL ?>assets/images/avatar/<?= htmlspecialchars($_SESSION['avatar_url']) ?>"
                        alt="Avatar">

                <?php else: ?>

                    <img
                        src="<?= BASE_URL ?>assets/images/avatar/avatar.png"
                        alt="Avatar">

                <?php endif; ?>

                <div class="user-info">

                    <span class="name">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </span>

                    <small>Administrator</small>

                </div>

                <a href="logout.php" class="logout-btn">
                    Đăng xuất
                </a>

            </div>

        </div>

        <!-- CONTENT -->

        <div class="order-detail-container">


            <div class="detail-header">

                <div>

                    <h1>
                        Chi tiết đơn hàng
                    </h1>

                    <p>
                        Mã đơn #ORD-<?= $order['order_id'] ?>
                    </p>

                </div>

                <a href="orders.php" class="btn-back">

                    <i class="fas fa-arrow-left"></i>

                    Quay lại

                </a>

            </div>

            <!-- INFO -->

            <div class="detail-grid">

                <!-- Đơn hàng -->

                <div class="detail-card">

                    <h3>
                        Thông tin đơn hàng
                    </h3>

                    <div class="info-row">
                        <span>Mã đơn:</span>
                        <strong>#ORD-<?= $order['order_id'] ?></strong>
                    </div>

                    <div class="info-row">
                        <span>Ngày đặt:</span>
                        <strong>
                            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Trạng thái:</span>

                        <span class="badge <?= $order['status'] ?>">
                            <?= $statusText ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span>Tổng tiền:</span>

                        <strong class="price">
                            <?= number_format($order['total_amount'], 0, ',', '.') ?> ₫
                        </strong>
                    </div>

                </div>

                <!-- Khách hàng -->

                <div class="detail-card">

                    <h3>
                        Thông tin khách hàng
                    </h3>

                    <div class="customer-box">

                        <div class="avatar">

                            <?= strtoupper(substr($order['username'], 0, 1)) ?>

                        </div>

                        <div>

                            <div class="customer-name">
                                <?= htmlspecialchars($order['username']) ?>
                            </div>

                            <div class="customer-email">
                                <?= htmlspecialchars($order['email']) ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- DANH SÁCH SẢN PHẨM -->

            <div class="detail-card">

                <h3>
                    Sản phẩm đã mua
                </h3>

                <table class="products-table">

                    <thead>

                        <tr>

                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($item = $items->fetch_assoc()): ?>

                            <tr>

                                <td>

                                    <div class="product-info">

                                        <img
                                            src="<?= BASE_URL . $item['thumbnail_url'] ?>"
                                            alt="">

                                        <span>
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </span>

                                    </div>

                                </td>

                                <td>
                                    <?= number_format($item['price']) ?> ₫
                                </td>

                                <td>
                                    <?= $item['quantity'] ?>
                                </td>

                                <td>

                                    <?= number_format(
                                        $item['price'] * $item['quantity']
                                    ) ?>

                                    ₫

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

            <!-- ACTION -->

            <div class="detail-card">

                <h3>
                    Cập nhật trạng thái
                </h3>

                <form method="POST">

                    <select name="status">

                        <option value="pending">
                            Chờ xác nhận
                        </option>

                        <option value="processing">
                            Đang xử lý
                        </option>

                        <option value="completed">
                            Hoàn thành
                        </option>

                        <option value="cancelled">
                            Đã huỷ
                        </option>

                    </select>

                    <button type="submit" class="btn-save">

                        <i class="fas fa-save"></i>

                        Cập nhật

                    </button>

                </form>

            </div>

        </div>

        <?php require '../includes/footer.php'; ?>

    </div>

</body>

</html>