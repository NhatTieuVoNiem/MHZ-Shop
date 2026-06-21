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
require_once("../../CONTROLLER/controller_orders.php");
/** @var mysqli_result $orders */

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
        href="<?= BASE_URL ?>style/orders.css?v=<?= time() ?>">

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

        <div class="orders-content">

            <div class="page-header">

                <div class="page-title">

                    <i class="fas fa-shopping-cart"></i>

                    Quản lý đơn hàng

                </div>

            </div>

            <!-- STATS -->

            <div class="stats-row">

                <div class="stat-card">

                    <div class="stat-label">
                        Tổng đơn hàng
                    </div>

                    <div class="stat-val purple">
                        <?= number_format($totalOrders) ?>
                    </div>

                    <div class="stat-sub">
                        Tất cả thời gian
                    </div>

                </div>

                <div class="stat-card">

                    <div class="stat-label">
                        Hoàn thành
                    </div>

                    <div class="stat-val cyan">
                        <?= number_format($completedOrders) ?>
                    </div>

                    <div class="stat-sub">
                        Đơn thành công
                    </div>

                </div>

                <div class="stat-card">

                    <div class="stat-label">
                        Đang xử lý
                    </div>

                    <div class="stat-val yellow">
                        <?= number_format($processingOrders) ?>
                    </div>

                    <div class="stat-sub">
                        Chờ xử lý
                    </div>

                </div>

                <div class="stat-card">

                    <div class="stat-label">
                        Đã huỷ
                    </div>

                    <div class="stat-val red">
                        <?= number_format($cancelledOrders) ?>
                    </div>

                    <div class="stat-sub">
                        Không thành công
                    </div>

                </div>

            </div>

            <!-- TABLE -->

            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>

                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $status_map = [

                            'completed' => [
                                'label' => 'Hoàn thành',
                                'class' => 'completed',
                                'icon' => 'fas fa-check'
                            ],

                            'processing' => [
                                'label' => 'Đang xử lý',
                                'class' => 'processing',
                                'icon' => 'fas fa-spinner'
                            ],

                            'pending' => [
                                'label' => 'Chờ xác nhận',
                                'class' => 'pending',
                                'icon' => 'fas fa-clock'
                            ],

                            'cancelled' => [
                                'label' => 'Đã huỷ',
                                'class' => 'cancelled',
                                'icon' => 'fas fa-times'
                            ]

                        ];

                        while ($o = $orders->fetch_assoc()) :

                            $st = $status_map[$o['status']]
                                ?? $status_map['pending'];

                            $name = $o['username'];

                            $parts = explode(' ', $name);

                            $initials =
                                mb_strtoupper(
                                    mb_substr($parts[0], 0, 1)
                                        .
                                        mb_substr(end($parts), 0, 1)
                                );

                        ?>

                            <tr>

                                <td class="order-id">
                                    <h3>
                                        #ORD-<?= str_pad(
                                                    $o['order_id'],
                                                    8,
                                                    "0",
                                                    STR_PAD_LEFT
                                                ) ?>
                                    </h3>
                                </td>

                                <td>

                                    <div class="customer-cell">

                                        <div class="cust-av">

                                            <?= $initials ?>

                                        </div>

                                        <div>

                                            <div class="cust-name">
                                                <?= htmlspecialchars($o['username']) ?>
                                            </div>

                                            <div class="cust-email">
                                                <?= htmlspecialchars($o['email']) ?>
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                <td class="product-name">
                                    <?= htmlspecialchars($o['product_name']) ?>
                                </td>

                                <td class="price">
                                    <?= number_format($o['total_amount'], 0, ',', '.') ?> ₫
                                </td>

                                <td class="date">
                                    <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?>
                                </td>

                                <td>

                                    <span class="badge <?= $st['class'] ?>">

                                        <i class="<?= $st['icon'] ?>"></i>

                                        <?= $st['label'] ?>

                                    </span>

                                </td>

                                <td>

                                    <div class="row-actions">

                                        <a href="order-detail.php?id=<?= $o['order_id'] ?>"
                                            class="btn-view">

                                            Xem

                                        </a>

                                        <a href="order-delete.php?id=<?= $o['order_id'] ?>"
                                            class="btn-del"
                                            onclick="return confirm('Xóa đơn hàng này?')">

                                            <i class="fas fa-trash"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <?php require '../includes/footer.php'; ?>

    </div>

</body>

</html>