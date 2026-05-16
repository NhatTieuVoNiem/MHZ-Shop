<?php /** @var array $item */ ?>
<?php /** @var Product $productModel */ ?>
<article class="section__content--card">
  <figure class="content__card--banner">
    <img
  src="<?= BASE_URL . htmlspecialchars($item['thumbnail_url']) ?>"
  alt="<?= htmlspecialchars($item['product_name']) ?>"
  onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'"
/>
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
        <span><?= $productModel->countViews($item['product_id']) ?></span>
      </div>
    </div>
    <a href="<?= BASE_URL ?>page/productsDetails.php?id=<?= (int)$item['product_id'] ?>" class="btn">
      Xem chi tiết
    </a>
  </div>
</article>