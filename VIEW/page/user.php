<?php
session_start();

if (!isset($_SESSION['account_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SESSION['role_id'] != 2) {
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

/* ==========================
   THỐNG KÊ
========================== */

$orders = $orderModel->getOrdersByAccount($accountId);
$totalOrders = $orders->num_rows;

$totalWishlist = $conn->query("
    SELECT COUNT(*) total
    FROM product_likes
    WHERE account_id = $accountId
")->fetch_assoc()['total'];

$totalReviews = $conn->query("
    SELECT COUNT(*) total
    FROM product_reviews
    WHERE account_id = $accountId
")->fetch_assoc()['total'];

$balance = 0;

/* ==========================
   ĐÃ XEM GẦN ĐÂY
========================== */

$recentViews = $conn->query("
    SELECT
        p.product_id,
        p.product_name,
        p.thumbnail_url,
        p.price,
        MAX(pv.viewed_at) viewed_at

    FROM product_views pv

    JOIN products p
        ON pv.product_id = p.product_id

    WHERE pv.account_id = $accountId

    GROUP BY p.product_id

    ORDER BY viewed_at DESC

    LIMIT 8
");

/* ==========================
   GỢI Ý
========================== */

$suggestions = $productModel->getTrendingProducts(8);

/* ==========================
   TOP SELLER
========================== */

$topCreators = $accountModel->getTopCreators(5);
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
  <link rel="stylesheet" href="<?= BASE_URL ?>style/user.css?v=<?= time() ?>">
</head>

<body>

  <div class="wrapper">

    <?php require '../includes/nav_menu_login.php'; ?>

    <div class="content">

      <?php require '../includes/header.php'; ?>

      <main class="body">

        <!-- WELCOME -->
        <section class="welcome-section">

          <div class="welcome-content">

            <div>

              <h1>
                Xin chào,
                <?= htmlspecialchars($_SESSION['username']) ?>
                👋
              </h1>

              <p>
                Chào mừng quay trở lại MHZ Shop.
                Khám phá các sản phẩm số mới nhất và ưu đãi dành riêng cho bạn.
              </p>

              <div class="quick-action">

                <a href="products.php" class="btn btn__view">
                  Khám phá ngay
                </a>

                <a href="order_user.php"
                  class="btn btn__view btn__view--red">
                  Đơn hàng của tôi
                </a>

              </div>

            </div>

            <div class="welcome-avatar">

              <img
                src="<?= $_SESSION['avatar_url'] ?? '../assets/images/avatar/avatar.png' ?>"
                alt="Avatar">

            </div>

          </div>

        </section>

        <!-- DASHBOARD -->
        <section class="dashboard-cards">

          <div class="card">
            <i class="fa-solid fa-cart-shopping"></i>

            <div>
              <h3>Đơn hàng</h3>
              <span><?= $totalOrders ?></span>
            </div>
          </div>

          <div class="card">
            <i class="fa-solid fa-heart"></i>

            <div>
              <h3>Yêu thích</h3>
              <span><?= $totalWishlist ?></span>
            </div>
          </div>

          <div class="card">
            <i class="fa-solid fa-star"></i>

            <div>
              <h3>Đánh giá</h3>
              <span><?= $totalReviews ?></span>
            </div>
          </div>

          <div class="card">
            <i class="fa-solid fa-wallet"></i>

            <div>
              <h3>Số dư</h3>
              <span><?= number_format($balance) ?> đ</span>
            </div>
          </div>

        </section>

        <!-- ĐÃ XEM -->
        <section class="trending-section">

          <div class="section__head">

            <h2 class="section__head--title">
              🕒 Đã xem gần đây
            </h2>

          </div>

          <div class="section__content">

            <?php if ($recentViews->num_rows > 0): ?>

              <?php while ($item = $recentViews->fetch_assoc()): ?>

                <div class="section__content--card">

                  <div class="content__card--banner">

                    <img
                      src="<?= htmlspecialchars($item['thumbnail_url']) ?>"
                      alt=""
                      onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                  </div>

                  <div class="content__card--info">

                    <h3>
                      <?= htmlspecialchars($item['product_name']) ?>
                    </h3>

                    <div class="fames">

                      <div class="fames__line">
                        <span>Giá</span>

                        <span>
                          <?= number_format($item['price']) ?>
                          VNĐ
                        </span>
                      </div>

                    </div>

                    <a
                      href="productsDetails.php?id=<?= $item['product_id'] ?>"
                      class="btn btn__view">
                      Xem chi tiết
                    </a>

                  </div>

                </div>

              <?php endwhile; ?>

            <?php else: ?>

              <div class="empty-order">

                <i class="fa-solid fa-clock-rotate-left"></i>

                <p>
                  Bạn chưa xem sản phẩm nào
                </p>

              </div>

            <?php endif; ?>

          </div>

        </section>

        <!-- GỢI Ý -->
        <section class="trending-section">

          <div class="section__head">

            <h2 class="section__head--title">
              ✨ Gợi ý dành cho bạn
            </h2>

            <a href="products.php" class="view-more">
              Xem thêm
            </a>

          </div>

          <div class="section__content">

            <?php while ($item = $suggestions->fetch_assoc()): ?>

              <div class="section__content--card">

                <div class="content__card--banner">

                  <img
                    src="<?= htmlspecialchars($item['thumbnail_url']) ?>"
                    alt=""
                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                </div>

                <div class="content__card--info">

                  <h3>
                    <?= htmlspecialchars($item['product_name']) ?>
                  </h3>

                  <div class="fames">

                    <div class="fames__line">
                      <span>Giá</span>

                      <span>
                        <?= number_format($item['price']) ?>
                        VNĐ
                      </span>
                    </div>

                    <div class="fames__line">
                      <span>Lượt xem</span>

                      <span>
                        <?= $item['total_views'] ?>
                      </span>
                    </div>

                  </div>

                  <a
                    href="productsDetails.php?id=<?= $item['product_id'] ?>"
                    class="btn btn__view">
                    Xem chi tiết
                  </a>

                </div>

              </div>

            <?php endwhile; ?>

          </div>

        </section>

        <!-- TOP SELLER -->
        <section class="recent-section">

          <div class="creator">

            <div class="creator__head">
              <h2>🏆 Người bán nổi bật</h2>
            </div>

            <div class="creator__body">

              <?php while ($seller = $topCreators->fetch_assoc()): ?>

                <div class="activity__card">

                  <div class="activity__card--image">

                    <img
                      src="<?= $seller['avatar_url'] ?: '../assets/images/avatar/avatar.png' ?>"
                      alt="">

                  </div>

                  <div class="activity__card--text">

                    <div class="activity__card--text-left">

                      <a
                        href="profile.php?id=<?= $seller['account_id'] ?>"
                        class="seller-link">
                        <?= htmlspecialchars($seller['username']) ?>
                      </a>

                      <span>
                        <?= $seller['total_items'] ?>
                        sản phẩm
                      </span>

                    </div>

                    <div class="activity__card--text-right">
                      🔥 Top Seller
                    </div>

                  </div>

                </div>

              <?php endwhile; ?>

            </div>

          </div>

        </section>

      </main>

      <?php require '../includes/footer.php'; ?>

    </div>

  </div>

</body>

</html>