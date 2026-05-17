<?php
require_once("../MODEL/Product.php");

// Tạo object Product
$productModel = new Product($conn);

// Lấy tất cả sản phẩm
$products = $productModel->getTrendingProducts();
?>

<section class="trending-section container" aria-label="Trending Bids">
  <div class="section__head">
    <h2 class="section__head--title">Trending Bids</h2>

       <a
      href="<?= BASE_URL ?>page/products.php"
      class="view-more">
      >> Xem thêm
    </a>
  </div>

  <div class="section__content">

    <?php if ($products && $products->num_rows > 0): ?>

      <?php while ($item = $products->fetch_assoc()): ?>

        <article class="section__content--card">

          <figure class="content__card--banner">
            <img
              src="<?= BASE_URL . htmlspecialchars($item['thumbnail_url']) ?>"
              alt="<?= htmlspecialchars($item['product_name']) ?>"
              onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'" />
          </figure>

          <div class="content__card--info">

            <h3>
              <?= htmlspecialchars($item['product_name']) ?>
            </h3>

            <div class="fames">

              <div class="fames__line">
                <span>Ngày đăng</span>

                <div class="fames__line--right">
                  <span>Giá bán</span>
                </div>
              </div>

              <div class="fames__line">
                <span>
                  <?= date("d/m/Y", strtotime($item['created_at'])) ?>
                </span>

                <span>
                  <?= htmlspecialchars($item['price']) ?> ETH
                </span>
              </div>

            </div>
<form id="detail-form" method="POST" action="<?= BASE_URL ?>../CONTROLLER/controller_products_view.php">
    <input type="hidden" name="action" value="trackView">
    <input type="hidden" name="product_id" id="detail-product-id" value="">
    <input type="hidden" name="redirect_url" id="detail-redirect-url" value="">
</form>
          <a
  href="javascript:void(0)"
  class="btn btn-detail"
  data-product-id="<?= htmlspecialchars($item['product_id']) ?>"
  data-detail-url="<?= BASE_URL ?>../VIEW/page/productsDetails.php?id=<?= $item['product_id'] ?>">
  Xem chi tiết
</a>

          </div>

        </article>

      <?php endwhile; ?>

    <?php else: ?>

      <p>Không có sản phẩm nào.</p>

    <?php endif; ?>

  </div>
</section>