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
require_once("../../MODEL/Product.php");

$productModel = new Product($conn);

/* Featured */
$featured = $productModel->getFeaturedNFT();

/* Trending */
$trendingProducts = $productModel->getTrendingProducts(10);

/* Pagination */
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$totalResult = $productModel->getAll();
$totalProducts = mysqli_num_rows($totalResult);
$totalPages = ceil($totalProducts / $limit);

$sql = "
SELECT
    p.*,
    a.username,
    c.category_name
FROM products p
LEFT JOIN accounts a
    ON a.account_id = p.account_id
LEFT JOIN categories c
    ON c.category_id = p.category_id
ORDER BY p.created_at DESC
LIMIT $limit OFFSET $offset
";

$products = $conn->query($sql);
$category_id = isset($_POST['category_id'])
    ? (int)$_POST['category_id']
    : 0;
$categories = $conn->query("
    SELECT category_id, category_name
    FROM categories
    ORDER BY category_name
");
/* Sản phẩm đã gỡ */
$deletedProducts = $conn->query("
    SELECT
        p.*,
        a.username,
        c.category_name
    FROM products p
    LEFT JOIN accounts a
        ON a.account_id = p.account_id
    LEFT JOIN categories c
        ON c.category_id = p.category_id
    WHERE p.status = 0  
    ORDER BY p.created_at DESC
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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/products-admin.css?v=<?= time() ?>" />
</head>

<body>
    <div class="wrapper">
        <div class="admin-header">

            <a href="./admin.php" class="logo">
                <h2>MHZ Admin</h2>
            </a>

            <nav class="admin-nav">
                <a href="admin.php" ">
                    <i class=" fas fa-home"></i>
                    Dashboard
                </a>

                <a href="accounts.php">
                    <i class="fas fa-users"></i>
                    Tài khoản
                </a>

                <a href="products-admin.php" class="active">
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
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
                    </span>
                    <small>Administrator</small>
                </div>

                <a href="logout.php" class="logout-btn">
                    Đăng xuất
                </a>

            </div>

        </div>
        <div class="featured-product">

            <div class="title_box">
                <h2>🔥 Sản Phẩm Nổi Bật</h2>
            </div>

            <?php if ($featured): ?>

                <div class="featured-card">

                    <img
                        src="<?= $featured['thumbnail_url'] ?>"
                        alt=""
                        onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                    <div class="featured-info">

                        <h3>
                            <?= htmlspecialchars($featured['product_name']) ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($featured['description']) ?>
                        </p>

                        <span class="price">
                            <?= number_format($featured['price']) ?> VNĐ
                        </span>

                        <small>
                            Đăng bởi:
                            <?= htmlspecialchars($featured['username']) ?>
                        </small>

                    </div>

                </div>

            <?php endif; ?>

        </div>
        <div class="list_products">

            <div class="title_box">
                <h2>📈 Top Sản Phẩm Nhiều Lượt Xem</h2>
            </div>

            <div class="seller_list">

                <?php
                $rank = 1;
                while ($product = mysqli_fetch_assoc($trendingProducts)):
                ?>

                    <div class="seller_card">

                        <span class="rank">
                            #<?= $rank++ ?>
                        </span>

                        <img
                            src="<?= $product['thumbnail_url'] ?>"
                            alt=""
                            onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                        <div class="seller_info">

                            <h3>
                                <?= htmlspecialchars($product['product_name']) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars($product['username']) ?>
                            </p>

                            <span class="revenue">
                                <?= number_format($product['price']) ?> VNĐ
                            </span>

                            <small>
                                👁 <?= $product['total_views'] ?> lượt xem
                            </small>

                        </div>

                        <div class="seller_actions">

                            <button
                                class="btn-action btn-edit openEditModal"
                                data-id="<?= $product['product_id'] ?>">
                                ✏️ Sửa
                            </button>

                            <a
                                href="products_delete.php?id=<?= $product['product_id'] ?>"
                                class="btn-action btn-delete"
                                onclick="return confirm('Xóa sản phẩm này?')">
                                🗑 Xóa
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        </div>
        <div class="list_deleted_products">

            <div class="title_box">
                <h2>🗑️ Sản Phẩm Đã Gỡ</h2>
            </div>

            <div class="seller_list">

                <?php if ($deletedProducts && mysqli_num_rows($deletedProducts) > 0): ?>

                    <?php while ($product = mysqli_fetch_assoc($deletedProducts)): ?>

                        <div class="seller_card deleted-card">

                            <img
                                src="<?= $product['thumbnail_url'] ?>"
                                alt=""
                                onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                            <div class="seller_info">

                                <h3>
                                    <?= htmlspecialchars($product['product_name']) ?>
                                </h3>

                                <p>
                                    Danh mục:
                                    <?= htmlspecialchars($product['category_name']) ?>
                                </p>

                                <small>
                                    Người bán:
                                    <?= htmlspecialchars($product['username']) ?>
                                </small>

                                <span class="revenue">
                                    <?= number_format($product['price']) ?> VNĐ
                                </span>

                                <small>
                                    <?= $product['created_at'] ?>
                                </small>

                            </div>

                            <div class="seller_actions">

                                <a
                                    href="products_restore.php?id=<?= $product['product_id'] ?>"
                                    class="btn-action btn-restore"
                                    onclick="return confirm('Khôi phục sản phẩm này?')">
                                    ♻️ Khôi phục
                                </a>

                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p style="padding:20px;text-align:center;">
                        Chưa có sản phẩm nào bị gỡ.
                    </p>

                <?php endif; ?>

            </div>

        </div>
        <div class="list_all_accounts">

            <div class="title_box">
                <h2>🎮 Tất Cả Sản Phẩm</h2>
            </div>

            <div class="seller_list">

                <?php while ($product = mysqli_fetch_assoc($products)): ?>

                    <div class="seller_card">

                        <img
                            src="<?= $product['thumbnail_url'] ?>"
                            alt=""
                            onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                        <div class="seller_info">

                            <h3>
                                <?= htmlspecialchars($product['product_name']) ?>
                            </h3>

                            <p>
                                Danh mục:
                                <?= htmlspecialchars($product['category_name']) ?>
                            </p>

                            <small>
                                Người bán:
                                <?= htmlspecialchars($product['username']) ?>
                            </small>

                            <span class="revenue">
                                <?= number_format($product['price']) ?> VNĐ
                            </span>

                            <small>
                                <?= $product['created_at'] ?>
                            </small>

                        </div>

                        <div class="seller_actions">

                            <button
                                class="btn-action btn-edit openEditModal"
                                data-id="<?= $product['product_id'] ?>">
                                ✏️ Sửa
                            </button>

                            <a
                                href="products_delete.php?id=<?= $product['product_id'] ?>"
                                class="btn-action btn-delete"
                                onclick="return confirm('Xóa sản phẩm này?')">
                                🗑 Xóa
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

            <div class="pagination">

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                    <a href="?page=<?= $i ?>"
                        class="<?= $i == $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>

                <?php endfor; ?>

            </div>

        </div>
        <?php require '../includes/footer.php'; ?>
        <div id="editModal" class="modal">

            <div class="modal-content">

                <span class="close-modal">&times;</span>

                <h2>Cập Nhật Sản Phẩm</h2>

                <form
                    action="../../CONTROLLER/controller_product_admin.php"
                    method="POST">

                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="product_id" id="product_id">

                    <div class="form-group">
                        <label>Tên sản phẩm</label>
                        <input
                            type="text"
                            name="product_name"
                            id="product_name"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Giá</label>
                        <input
                            type="number"
                            name="price"
                            id="price"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Danh mục</label>
                        <select name="category_id" id="category_id">
                            <?php while ($c = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $c['category_id'] ?>">
                                    <?= htmlspecialchars($c['category_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Thumbnail</label>
                        <input
                            type="text"
                            name="thumbnail_url"
                            id="thumbnail_url">
                    </div>

                    <div class="form-group">
                        <label>Preview URL</label>
                        <input
                            type="text"
                            name="preview_url"
                            id="preview_url">
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea
                            name="description"
                            id="description"
                            rows="5"></textarea>
                    </div>

                    <button
                        type="submit"
                        class="btn-save">
                        💾 Lưu Thay Đổi
                    </button>

                </form>

            </div>

        </div>
    </div>
    <script src="../js/products_admin.js?v=<?= time() ?>"></script>
</body>

</html>