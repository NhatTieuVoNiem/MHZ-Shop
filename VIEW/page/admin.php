<?php
// Đường dẫn gốc (dùng khi include từ các trang con)
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

require_once("../../MODEL/connect.php");

// Tổng người dùng
$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM accounts")
)['total'];

// Tổng người bán
$totalSellers = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM accounts a
        JOIN roles r ON a.role_id = r.role_id
        WHERE r.role_name = 'Seller'
    ")
)['total'];

// Tổng sản phẩm
$totalProducts = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM products")
)['total'];

// Tổng đơn hàng
$totalOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders")
)['total'];
$stats = [];

for ($i = 5; $i >= 0; $i--) {

    $month = date('m', strtotime("-$i month"));
    $year = date('Y', strtotime("-$i month"));

    $label = date('m/Y', strtotime("-$i month"));

    $user = $conn->query("
        SELECT COUNT(*) total
        FROM accounts
        WHERE MONTH(created_at) = $month
        AND YEAR(created_at) = $year
    ")->fetch_assoc()['total'];

    $product = $conn->query("
        SELECT COUNT(*) total
        FROM products
        WHERE MONTH(created_at) = $month
        AND YEAR(created_at) = $year
    ")->fetch_assoc()['total'];

    $order = $conn->query("
        SELECT COUNT(*) total
        FROM orders
        WHERE MONTH(created_at) = $month
        AND YEAR(created_at) = $year
    ")->fetch_assoc()['total'];

    $stats[] = [
        'month' => $label,
        'user' => $user,
        'product' => $product,
        'order' => $order,
        'total' => $user + $product + $order
    ];
}

$max = max(array_column($stats, 'total'));
// Tổng lượt like và review toàn hệ thống
$totalLikes = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM product_likes")
)['total'];

$totalComments = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM product_reviews")
)['total'];

// Truy vấn top 10 người dùng theo lượt tương tác
$topUsers = mysqli_query($conn, "
    SELECT 
        a.account_id,
        a.username,
        a.avatar_url,
        COUNT(DISTINCT pl.like_id) AS user_likes,
        COUNT(DISTINCT pr.review_id) AS user_comments,
        (
            (COUNT(DISTINCT pl.like_id) / IF($totalLikes=0,1,$totalLikes)) +
            (COUNT(DISTINCT pr.review_id) / IF($totalComments=0,1,$totalComments))
        ) AS interaction_score
    FROM accounts a
    LEFT JOIN product_likes pl ON a.account_id = pl.account_id
    LEFT JOIN product_reviews pr ON a.account_id = pr.account_id
    GROUP BY a.account_id, a.username, a.avatar_url
    ORDER BY interaction_score DESC
    LIMIT 10
");


$topProducts = mysqli_query($conn, "
    SELECT 
        p.product_id,
        p.product_name,
        p.thumbnail_url,
        SUM(o.quantity) AS sold_quantity,
        SUM(o.quantity * p.price) AS revenue,
        RANK() OVER (ORDER BY SUM(o.quantity) DESC) AS rank
    FROM order_items o
    INNER JOIN products p ON o.product_id = p.product_id
    GROUP BY p.product_id, p.product_name, p.thumbnail_url, p.price
    ORDER BY sold_quantity DESC
    LIMIT 10
");
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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/footer.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/admin.css?v=<?= time() ?>" />
</head>

<body>
    <div class="wrapper">
        <!-- header -->
        <div class="admin-header">

            <a href="./admin.php" class="logo">
                <h2>MHZ Admin</h2>
            </a>

            <nav class="admin-nav">
                <a href="admin.php" class="active">
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

                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i>
                    Đơn hàng
                </a>

                <a href="reports.php">
                    <i class="fas fa-chart-line"></i>
                    Báo cáo
                </a>
            </nav>

            <div class="admin-user">

                <?php if (!empty($user['avatar'])): ?>
                    <img
                        src="<?= BASE_URL . 'assets/images/avatar/' . $_SESSION['avatar_url'] ?>"
                        alt="Avatar">
                <?php else: ?>
                    <img
                        src="<?= BASE_URL ?>assets/images/avatar/avatar.png"
                        alt="Avatar">
                <?php endif; ?>

                <div class="user-info">
                    <span class="name">
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
                    </span>
                    <small>Administrator</small>
                </div>

                <a href="logout.php" class="logout-btn">
                    Đăng xuất
                </a>

            </div>

        </div>
        <!-- tổng hợp -->
        <div class="section">

            <a href="accounts.php" class="card user-card">
                <div class="card-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="card-content">
                    <span class="title">Người dùng</span>
                    <span class="number"><?= $totalUsers ?></span>
                </div>
            </a>

            <a href="accounts.php" class="card seller-card">
                <div class="card-icon">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div class="card-content">
                    <span class="title">Người bán</span>
                    <span class="number"><?= $totalSellers ?></span>
                </div>
            </a>

            <a href="products-admin.php" class="card product-card">
                <div class="card-icon">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="card-content">
                    <span class="title">Sản phẩm</span>
                    <span class="number"><?= $totalProducts ?></span>
                </div>
            </a>

            <a href="orders.php" class="card order-card">
                <div class="card-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="card-content">
                    <span class="title">Đơn hàng</span>
                    <span class="number"><?= $totalOrders ?></span>
                </div>
            </a>

        </div>
        <!-- biểu đồ -->
        <div class="section">
            <div class="chart-wrapper">

                <div class="chart-container">

                    <h3>Thống kê 6 tháng gần nhất</h3>

                    <div class="chart">

                        <?php foreach ($stats as $item): ?>

                            <?php
                            $userHeight = ($item['user'] / max($max, 1)) * 250;
                            $productHeight = ($item['product'] / max($max, 1)) * 250;
                            $orderHeight = ($item['order'] / max($max, 1)) * 250;
                            ?>

                            <div class="month-group">

                                <div class="bars">

                                    <div
                                        class="bar user-bar"
                                        style="height: <?= $userHeight ?>px"
                                        title="Tài khoản: <?= $item['user'] ?>">
                                    </div>

                                    <div
                                        class="bar product-bar"
                                        style="height: <?= $productHeight ?>px"
                                        title="Sản phẩm: <?= $item['product'] ?>">
                                    </div>

                                    <div
                                        class="bar order-bar"
                                        style="height: <?= $orderHeight ?>px"
                                        title="Đơn hàng: <?= $item['order'] ?>">
                                    </div>

                                </div>

                                <span><?= $item['month'] ?></span>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <div class="legend">
                        <span><i class="blue"></i> Tài khoản</span>
                        <span><i class="green"></i> Sản phẩm</span>
                        <span><i class="orange"></i> Đơn hàng</span>
                    </div>

                </div>
            </div>
        </div>
        <!-- top người dùng -->
        <div class="section">
            <table class="top-users">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Tên người dùng</th>
                        <th>Lượt like</th>
                        <th>Lượt comment</th>
                        <th>Điểm tương tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($topUsers)): ?>
                        <tr>
                            <td>
                                <img src="<?= BASE_URL . 'assets/images/avatar/' . ($row['avatar_url'] ?: 'avatar.png') ?>"
                                    alt="Avatar" width="40" height="40">
                            </td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['user_likes'] ?></td>
                            <td><?= $row['user_comments'] ?></td>
                            <td><?= number_format($row['interaction_score'], 4) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <!-- top sản phẩm -->
        <div class="section">

            <table class="top-products">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng bán</th>
                        <th>Doanh thu</th>
                        <th>Xếp hạng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($topProducts)): ?>
                        <tr>
                            <td>
                                <img src="<?= BASE_URL . 'assets/images/products/' . ($row['thumbnail_url'] ?: 'Copilot_20260504_143121.png') ?>"
                                    alt="Product" width="50" height="50"
                                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">
                            </td>

                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= $row['sold_quantity'] ?></td>
                            <td><?= number_format($row['revenue'], 0) ?> đ</td>
                            <td>#<?= $row['rank'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php require '../includes/footer.php'; ?>
    </div>
</body>

</html>