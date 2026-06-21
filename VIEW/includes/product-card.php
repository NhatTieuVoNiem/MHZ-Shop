<?php

/**
 * Thẻ sản phẩm dùng lại trên products.php
 * @var array   $item          Một dòng sản phẩm từ DB
 * @var Product $productModel  Gọi countViews() hiển thị lượt xem
 */
?>
<article class="section__content--card">
  <figure class="content__card--banner">
    <img
      src="<?= BASE_URL . htmlspecialchars($item['thumbnail_url']) ?>"
      alt="<?= htmlspecialchars($item['product_name']) ?>"
      onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'" />
  </figure>
  <div class="content__card--info">
    <h3><?= htmlspecialchars($item['product_name']) ?></h3>
    <div class="fames">
      <div class="fames__line">
        <span>Ngày đăng</span>
        <div class="fames__line--right"><span>Lượt xem</span></div>
      </div>
      <div class="fames__line">
        <span><?= date("d/m/Y", strtotime($item['created_at'])) ?></span>
        <span>
          <?= isset($productModel)
            ? $productModel->countViews($item['product_id'])
            : 0 ?>
        </span>
      </div>
    </div>
    <?php /* POST trackView → controller_products_view.php → redirect trang chi tiết */ ?>
    <form method="POST" action="<?= BASE_URL ?>../CONTROLLER/controller_products_view.php">
      <input type="hidden" name="action" value="trackView">
      <input type="hidden" name="product_id">
      <input type="hidden" name="redirect_url">
    </form>
    <a
      href="javascript:void(0)"
      class="btn btn-detail"
      data-product-id="<?= (int)$item['product_id'] ?>"
      data-detail-url="<?= BASE_URL ?>VIEW/page/productsDetails.php?id=<?= (int)$item['product_id'] ?>">
      Xem chi tiết
    </a>
  </div>
</article>