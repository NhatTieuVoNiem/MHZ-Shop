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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/product_manager.css?v=<?= time() ?>">
</head>

<body>

    <div class="wrapper">

        <?php require '../includes/nav_menu_login.php'; ?>

        <div class="content">

            <?php require '../includes/header.php'; ?>

            <main class="body">

                <?php
                $keyword = trim($_GET['keyword'] ?? '');

                if (!empty($keyword)) {
                    $products = $productModel->searchSellerProducts(
                        $accountId,
                        $keyword
                    );
                } else {
                    $products = $productModel->getByAccountId($accountId);
                }
                ?>

                <section class="seller-dashboard">

                    <!-- Banner -->
                    <div class="seller-banner">

                        <div class="banner-left">
                            <span class="banner-badge">
                                <i class="fa-solid fa-store"></i>
                                Seller Center
                            </span>

                            <h1>Kho sản phẩm</h1>

                            <p>
                                Quản lý sản phẩm, doanh thu và hoạt động kinh doanh
                                của bạn trên MHZ Shop.
                            </p>
                        </div>

                        <button
                            type="button"
                            class="btn-add-product"
                            onclick="openCreateModal()">

                            <i class="fa-solid fa-plus"></i>
                            Thêm sản phẩm mới
                        </button>

                    </div>

                    <!-- Statistics -->
                    <div class="dashboard-stats">

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>

                            <div>
                                <h3>
                                    <?= $productModel->countSellerProducts($accountId) ?>
                                </h3>
                                <p>Sản phẩm</p>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fa-solid fa-eye"></i>
                            </div>

                            <div>
                                <h3>
                                    <?= $productModel->countSellerViews($accountId) ?>
                                </h3>
                                <p>Lượt xem</p>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>

                            <div>
                                <h3>
                                    <?= $orderModel->countSellerOrders($accountId)  ?>
                                </h3>
                                <p>Đơn hàng</p>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>

                            <div>
                                <h3>
                                    <?= number_format($orderModel->getSellerRevenue($accountId)) ?>
                                </h3>
                                <p>Doanh thu</p>
                            </div>
                        </div>

                    </div>

                    <!-- Toolbar -->
                    <div class="toolbar">

                        <form method="GET" class="search-form">

                            <div class="search-box">

                                <i class="fa-solid fa-magnifying-glass"></i>

                                <input
                                    type="text"
                                    name="keyword"
                                    placeholder="Tìm kiếm sản phẩm..."
                                    value="<?= htmlspecialchars($keyword) ?>">

                                <button type="submit">
                                    Tìm kiếm
                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- Header -->
                    <div class="section-header">

                        <h2>
                            <i class="fa-solid fa-cube"></i>
                            Danh sách sản phẩm
                        </h2>

                        <span>
                            <?= count($products) ?> sản phẩm
                        </span>

                    </div>

                    <!-- Products -->
                    <div class="products-grid">

                        <?php if (!empty($products)): ?>

                            <?php foreach ($products as $product): ?>

                                <article class="product-card">

                                    <div class="product-image">

                                        <img
                                            src="<?= BASE_URL . htmlspecialchars($product['thumbnail_url']) ?>"
                                            alt="<?= htmlspecialchars($product['product_name']) ?>"
                                            onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                                        <?php if ($product['status'] == 1): ?>
                                            <span class="status-badge active">
                                                Đang bán
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge inactive">
                                                Dừng bán
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <div class="product-content">

                                        <h3>
                                            <?= htmlspecialchars($product['product_name']) ?>
                                        </h3>

                                        <div class="product-price">
                                            <?= number_format($product['price']) ?> VNĐ
                                        </div>

                                        <div class="product-meta">

                                            <span>
                                                <i class="fa-solid fa-eye"></i>
                                                <?= $productModel->countViews($product['product_id']) ?>
                                            </span>

                                            <span>
                                                <i class="fa-solid fa-calendar"></i>
                                                <?= date(
                                                    'd/m/Y',
                                                    strtotime($product['created_at'])
                                                ) ?>
                                            </span>

                                        </div>

                                        <div class="product-actions">

                                            <button
                                                type="button"
                                                class="btn-edit"
                                                onclick="openEditModal(
                                                    <?= $product['product_id'] ?>,
                                                    '<?= htmlspecialchars(addslashes($product['product_name'])) ?>',
                                                    '<?= htmlspecialchars(addslashes($product['description'])) ?>',
                                                    <?= $product['price'] ?>
                                                )">

                                                <i class="fa-solid fa-pen"></i>
                                                Cập nhật
                                            </button>

                                            <a
                                                href="../../CONTROLLER/controller_product_delete.php?id=<?= $product['product_id'] ?>"
                                                class="btn-delete"
                                                onclick="return confirm('Bạn có chắc muốn gỡ sản phẩm này?')">

                                                <i class="fa-solid fa-trash"></i>
                                                Gỡ bỏ
                                            </a>

                                        </div>

                                    </div>

                                </article>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="empty-product">

                                <i class="fa-solid fa-box-open"></i>

                                <h3>
                                    Chưa có sản phẩm nào
                                </h3>

                                <p>
                                    Hãy đăng sản phẩm đầu tiên của bạn.
                                </p>

                                <a
                                    href="product_create.php"
                                    class="btn-add-product">

                                    <i class="fa-solid fa-plus"></i>
                                    Thêm sản phẩm
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </section>
                <div id="createModal" class="modal">

                    <div class="modal-content">

                        <span class="close" onclick="closeCreateModal()">
                            &times;
                        </span>

                        <h2>Thêm sản phẩm</h2>

                        <form
                            action="../../CONTROLLER/controller_product_create.php"
                            method="POST"
                            enctype="multipart/form-data">

                            <input
                                type="text"
                                name="product_name"
                                placeholder="Tên sản phẩm"
                                required>

                            <textarea
                                name="description"
                                placeholder="Mô tả sản phẩm"
                                required></textarea>

                            <input
                                type="number"
                                name="price"
                                placeholder="Giá sản phẩm"
                                required>

                            <select name="category_id" required>
                                <?php
                                $categories = $conn->query("SELECT * FROM categories");

                                while ($c = $categories->fetch_assoc()):
                                ?>
                                    <option value="<?= $c['category_id'] ?>">
                                        <?= $c['category_name'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>

                            <input
                                type="file"
                                name="thumbnail"
                                accept="image/*"
                                required>

                            <button type="submit">
                                Lưu sản phẩm
                            </button>

                        </form>

                    </div>

                </div>
                <div id="editModal" class="modal">

                    <div class="modal-content">
                        <span class="close" onclick="closeEditModal()">
                            &times;
                        </span>
                        <h2>Cập nhật sản phẩm</h2>

                        <form
                            action="../../CONTROLLER/controller_product_update.php"
                            method="POST">

                            <input
                                type="hidden"
                                name="product_id"
                                id="edit_product_id">

                            <input
                                type="text"
                                name="product_name"
                                id="edit_product_name">

                            <textarea
                                name="description"
                                id="edit_description"></textarea>

                            <input
                                type="number"
                                name="price"
                                id="edit_price">

                            <button type="submit">
                                Cập nhật
                            </button>

                        </form>

                    </div>

                </div>
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
    <script src="<?= BASE_URL ?>js/clickProduct.js?v=<?= time() ?>"></script>
</body>

</html>