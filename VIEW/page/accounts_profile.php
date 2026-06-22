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

require_once("../../MODEL/connect.php");
require_once("../../CONTROLLER/controller_account.php");

$accountController = new controller_account($conn);

/*
|--------------------------------------------------------------------------
| Lấy thông tin tài khoản cần xem
|--------------------------------------------------------------------------
*/
$accountId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($accountId <= 0) {
    die("ID tài khoản không hợp lệ");
}

$user = $accountController->getAccountById($accountId);

if (!$user) {
    die("Không tìm thấy tài khoản");
}

/*
|--------------------------------------------------------------------------
| Danh sách sản phẩm
|--------------------------------------------------------------------------
| Nếu chưa có Product Controller thì để tạm rỗng
*/
$products = null;
$totalProducts = 0;

// Nếu có Product Controller thì mở phần này
/*
require_once("../../CONTROLLER/controller_product.php");

$productController = new controller_product($conn);

$products = $productController->getProductsBySeller($accountId);

if ($products instanceof mysqli_result) {
    $totalProducts = $products->num_rows;
}
*/

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ tài khoản - MHZ Shop</title>

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
        href="<?= BASE_URL ?>style/accounts.css?v=<?= time() ?>">

    <link rel="stylesheet"
        href="<?= BASE_URL ?>style/profile.css?v=<?= time() ?>">
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

                <a href="accounts.php" class="active">
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

                <?php if (!empty($_SESSION['avatar_url'])): ?>

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
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </span>

                    <small>Administrator</small>
                </div>

                <a href="logout.php" class="logout-btn">
                    Đăng xuất
                </a>

            </div>

        </div>

        <!-- PROFILE -->
        <?php require '../includes/profile.php'; ?>

        <!-- FOOTER -->
        <?php require '../includes/footer.php'; ?>

    </div>

</body>

</html>