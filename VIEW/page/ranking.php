<?php
// Đường dẫn gốc (dùng khi include từ các trang con)
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');
require_once("../../MODEL/connect.php");
require_once("../../MODEL/Product.php");

$productModel = new Product($conn);

$rankingViews = $productModel->getTrendingProducts();
$rankingSales = $productModel->getTrendingProducts();
$rankingLikes = $productModel->getTrendingProducts();
$rankingComments = $productModel->getTrendingProducts();

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
  <link rel="stylesheet" href="<?= BASE_URL ?>style/ranking.css?v=<?= time() ?>" />
</head>

<body>
  <div class="wrapper">
    <?php require '../includes/nav-menu.php'; ?>
    <div class="content">
      <?php require '../includes/header.php'; ?>

      <section class="ranking-page">

        <div class="ranking-page__header">

          <div>
            <h1 class="ranking-page__title">
              Bảng xếp hạng
            </h1>

            <p class="ranking-page__subtitle">
              Theo dõi các sản phẩm nổi bật nhất hôm nay
            </p>
          </div>

          <div class="ranking-filter">

            <button class="ranking-filter__btn active">
              Hôm nay
            </button>

            <button class="ranking-filter__btn">
              Tuần
            </button>

            <button class="ranking-filter__btn">
              Tháng
            </button>

          </div>

        </div>

        <!-- Ranking Views -->

        <div class="ranking-block">

          <div class="ranking-block__head">

            <h2>
              👁️ Xếp hạng lượt xem
            </h2>

            <a href="#">
              >> Xem thêm
            </a>

          </div>

          <div class="ranking-grid">

            <?php foreach ($rankingViews as $index => $item): ?>

              <article class="ranking-card">

                <div class="ranking-card__top">

                  <div class="ranking-number">
                    #<?= $index + 1 ?>
                  </div>

                  <img
                    src="<?= htmlspecialchars($item['thumbnail_url']) ?>"
                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                    class="ranking-card__image"
                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                </div>

                <div class="ranking-card__content">

                  <h3>
                    <?= htmlspecialchars($item['product_name']) ?>
                  </h3>

                  <div class="ranking-meta">

                    <span>
                      👁️ 12.5K
                    </span>

                    <span>
                      💎 0.25 ETH
                    </span>

                  </div>

                  <a
                    href="productsDetails.php?id=<?= $item['product_id'] ?>"
                    class="ranking-btn">
                    Xem chi tiết
                  </a>

                </div>

              </article>

            <?php endforeach; ?>

          </div>

        </div>

        <!-- Ranking Sales -->

        <div class="ranking-block">

          <div class="ranking-block__head">

            <h2>
              💰 Xếp hạng lượt mua
            </h2>

            <a href="#">
              >> Xem thêm
            </a>

          </div>

          <div class="ranking-grid">

            <?php foreach ($rankingSales as $index => $item): ?>

              <article class="ranking-mini">

                <div class="ranking-mini__left">

                  <div class="ranking-mini__number">
                    #<?= $index + 1 ?>
                  </div>

                  <img
                    src="<?= htmlspecialchars($item['thumbnail_url']) ?>"
                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                </div>

                <div class="ranking-mini__content">

                  <h3>
                    <?= htmlspecialchars($item['product_name']) ?>
                  </h3>

                  <span>
                    🔥 520 lượt mua
                  </span>

                </div>

              </article>

            <?php endforeach; ?>

          </div>

        </div>

        <!-- Ranking Likes -->

        <div class="ranking-block">

          <div class="ranking-block__head">

            <h2>
              ❤️ Xếp hạng lượt thích
            </h2>

            <a href="#">
              >> Xem thêm
            </a>

          </div>

          <div class="ranking-grid">

            <?php foreach ($rankingLikes as $index => $item): ?>

              <article class="ranking-mini">

                <div class="ranking-mini__left">

                  <div class="ranking-mini__number">
                    #<?= $index + 1 ?>
                  </div>

                  <img
                    src="<?= htmlspecialchars($item['thumbnail_url']) ?>"
                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                </div>

                <div class="ranking-mini__content">

                  <h3>
                    <?= htmlspecialchars($item['product_name']) ?>
                  </h3>

                  <span>
                    ❤️ 8.2K lượt thích
                  </span>

                </div>

              </article>

            <?php endforeach; ?>

          </div>

        </div>

        <!-- Ranking Comments -->

        <div class="ranking-block">

          <div class="ranking-block__head">

            <h2>
              💬 Xếp hạng bình luận
            </h2>

            <a href="#">
              >> Xem thêm
            </a>

          </div>

          <div class="ranking-grid">

            <?php foreach ($rankingComments as $index => $item): ?>

              <article class="ranking-mini">

                <div class="ranking-mini__left">

                  <div class="ranking-mini__number">
                    #<?= $index + 1 ?>
                  </div>

                  <img
                    src="<?= htmlspecialchars($item['thumbnail_url']) ?>"
                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                </div>

                <div class="ranking-mini__content">

                  <h3>
                    <?= htmlspecialchars($item['product_name']) ?>
                  </h3>

                  <span>
                    💬 2.1K bình luận
                  </span>

                </div>

              </article>

            <?php endforeach; ?>

          </div>

        </div>

      </section>
      <?php require '../includes/footer.php'; ?>
    </div>

  </div>
  <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
</body>

</html>