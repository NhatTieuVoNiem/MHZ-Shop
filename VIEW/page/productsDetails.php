<?php
// Đường dẫn gốc (dùng khi include từ các trang con)
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');
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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/productsDetails.css?v=<?= time() ?>" />
</head>

<body>
    <div class="wrapper">
        <?php require '../includes/nav-menu.php'; ?>
        <div class="content">
            <?php require '../includes/header.php'; ?>

            <?php
            require_once("../../MODEL/connect.php");
            require_once("../../MODEL/Product.php");
            require_once("../../MODEL/ProductLike.php");
            require_once("../../MODEL/ProductReview.php");
            require_once("../../MODEL/ProductView.php");

            // Kiểm tra id sản phẩm
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                die("Sản phẩm không tồn tại");
            }

            $product_id = (int)$_GET['id'];

            // Khởi tạo model
            $productModel = new Product($conn);
            $likeModel    = new ProductLike($conn);
            $reviewModel  = new ProductReview($conn);
            $viewModel    = new ProductView($conn);

            // Lấy thông tin sản phẩm
            $product = $productModel->getById($product_id);

            if (!$product) {
                die("Không tìm thấy sản phẩm");
            }

            // =======================
            // THỐNG KÊ
            // =======================

            // Tổng lượt thích
            $totalLikes = 0;
            $likes = $likeModel->getAll();

            while ($row = $likes->fetch_assoc()) {
                if ($row['product_id'] == $product_id) {
                    $totalLikes++;
                }
            }

            // Tổng lượt xem
            $totalViews = 0;
            $views = $viewModel->getAll();

            while ($row = $views->fetch_assoc()) {
                if ($row['product_id'] == $product_id) {
                    $totalViews++;
                }
            }

            // Review sản phẩm
            $reviews = [];
            $totalRating = 0;
            $totalReview = 0;

            $reviewData = $reviewModel->getAll();

            while ($row = $reviewData->fetch_assoc()) {

                if ($row['product_id'] == $product_id) {

                    $reviews[] = $row;

                    $totalRating += $row['rating'];
                    $totalReview++;
                }
            }

            $avgRating = $totalReview > 0
                ? round($totalRating / $totalReview, 1)
                : 0;
            ?>

            <section class="product-detail container">

                <!-- LEFT -->
                <div class="product-detail__left">

                    <div class="product-image">
                        <img
                            src="<?= BASE_URL . htmlspecialchars($product['thumbnail_url']) ?>"
                            alt="<?= htmlspecialchars($product['product_name']) ?>"
                            onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'"/>
                    </div>

                </div>

                <!-- RIGHT -->
                <div class="product-detail__right">

                    <span class="product-category">
                        NFT Artwork
                    </span>

                    <h1 class="product-title">
                        <?= htmlspecialchars($product['product_name']) ?>
                    </h1>

                    <p class="product-desc">
                        <?= htmlspecialchars($product['description']) ?>
                    </p>

                    <!-- THỐNG KÊ -->
                    <div class="product-stats">

                        <div class="stat-box">
                            <h3><?= $totalLikes ?></h3>
                            <span>Lượt thích</span>
                        </div>

                        <div class="stat-box">
                            <h3><?= $totalViews ?></h3>
                            <span>Lượt xem</span>
                        </div>

                        <div class="stat-box">
                            <h3><?= $avgRating ?></h3>
                            <span>Đánh giá</span>
                        </div>

                    </div>

                    <!-- PRICE -->
                    <div class="product-price">

                        <span>Giá hiện tại</span>

                        <h2>
                            <?= htmlspecialchars($product['price']) ?> ETH
                        </h2>

                    </div>

                    <!-- ACTION -->
                    <div class="product-actions">

                        <a href="#" class="btn-buy buy-btn">
                            Đặt mua
                        </a>

                        <a href="#" class="btn-like">
                            Yêu thích
                        </a>
                        <form id="preview-form" method="POST" action="<?= BASE_URL ?>../CONTROLLER/controller_products_view.php">
    <input type="hidden" name="action" value="trackView">
    <input type="hidden" name="product_id" id="form-product-id" value="">
    <input type="hidden" name="redirect_url" id="form-redirect-url" value="">
</form>
                      <a
    href="javascript:void(0)"
    class="btn-view btn-preview"
    data-product-id="<?= htmlspecialchars($product['product_id']) ?>"
    data-preview-url="<?= htmlspecialchars(trim($product['preview_url'])) ?>">
    Xem trước
</a>
                    </div>

                    <!-- INFO -->
                    <div class="product-info">

                        <div class="info-item">
                            <span>Ngày đăng</span>
                            <strong>
                                <?= date("d/m/Y", strtotime($product['created_at'])) ?>
                            </strong>
                        </div>

                        <div class="info-item">
                            <span>Mã sản phẩm</span>
                            <strong>
                                #<?= $product['product_id'] ?>
                            </strong>
                        </div>

                    </div>

                </div>

            </section>

            <!-- REVIEW -->
            <section class="review-section container">

                <div class="section-title">
                    <h2>Đánh giá sản phẩm</h2>
                </div>

                <?php if (count($reviews) > 0): ?>

                    <div class="review-list">

                        <?php foreach ($reviews as $review): ?>

                            <div class="review-card">

                                <div class="review-top">

                                    <h4>
                                        User #<?= $review['account_id'] ?>
                                    </h4>

                                    <span>
                                        ⭐ <?= $review['rating'] ?>/5
                                    </span>

                                </div>

                                <p>
                                    <?= htmlspecialchars($review['comment']) ?>
                                </p>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <p class="empty-review">
                        Chưa có đánh giá nào
                    </p>

                <?php endif; ?>

            </section>

            <?php require '../includes/footer.php'; ?>
        </div>

    </div>
    <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
    <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
    <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
     <script src="<?= BASE_URL ?>js/productsView.js?v=<?= time() ?>"></script>
</body>

</html>