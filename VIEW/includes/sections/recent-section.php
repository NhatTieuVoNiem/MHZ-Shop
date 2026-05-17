<?php

require_once("../MODEL/connect.php");
require_once("../MODEL/Product.php");
require_once("../MODEL/Account.php");

$productModel = new Product($conn);
$accountModel = new Account($conn);

/*
|--------------------------------------------------------------------------
| Lấy dữ liệu
|--------------------------------------------------------------------------
*/
$recentActivities = $productModel->getRecentActivities(5);

$topCreators = $accountModel->getTopCreators(8);
?>
<section class="recent-section container" aria-label="Recent Activity">

  <!-- RECENT ACTIVITY -->
  <div class="activity">

    <div class="activity__head">
      <h3 class="bids__title">Recent Activity</h3>

      <a href="<?= BASE_URL ?>page/products.php">
        See More
      </a>
    </div>
    <form id="detail-form" method="POST" action="<?= BASE_URL ?>../CONTROLLER/controller_products_view.php">
      <input type="hidden" name="action" value="trackView">
      <input type="hidden" name="product_id" id="detail-product-id" value="">
      <input type="hidden" name="redirect_url" id="detail-redirect-url" value="">
    </form>
    <ul class="activity__body">

      <?php while ($item = $recentActivities->fetch_assoc()): ?>

        <li class="activity__item">
          <a
            href="javascript:void(0)"
            class="btn activity__card btn-detail"
            data-product-id="<?= htmlspecialchars($item['product_id']) ?>"
            data-detail-url="<?= BASE_URL ?>../VIEW/page/productsDetails.php?id=<?= $item['product_id'] ?>">
            <figure class="activity__card--image">

              <img
                src="<?= htmlspecialchars($item['thumbnail_url']) ?>"
                alt="<?= htmlspecialchars($item['product_name']) ?>"
                onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

            </figure>

            <div class="activity__card--text">

              <div class="activity__card--text-left">

                <span>
                  <?= htmlspecialchars($item['product_name']) ?>
                </span>

                <span>
                  Created by <?= htmlspecialchars($item['username']) ?>
                </span>

              </div>

              <time class="activity__card--text-right">

                <?php
                $created = strtotime($item['created_at']);
                $diff = time() - $created;

                if ($diff < 3600) {
                  echo floor($diff / 60) . " mins ago";
                } elseif ($diff < 86400) {
                  echo floor($diff / 3600) . " hours ago";
                } else {
                  echo floor($diff / 86400) . " days ago";
                }
                ?>

              </time>

            </div>
          </a>
        </li>

      <?php endwhile; ?>

    </ul>

  </div>

  <!-- TOP CREATORS -->
  <div class="creator">

    <div class="creator__head">
      <h3 class="bids__title">Top Creators</h3>
    </div>

    <ul class="creator__body">

      <?php while ($creator = $topCreators->fetch_assoc()): ?>

        <li class="activity__card">

          <figure class="activity__card--image">

            <img
              src="<?= !empty($creator['avatar_url'])
                      ? htmlspecialchars($creator['avatar_url'])
                      : BASE_URL . 'assets/images/default-avatar.png' ?>"
              alt="<?= htmlspecialchars($creator['username']) ?>"
              onerror="this.src='<?= BASE_URL ?>assets/images/avatar/avatar.png'">

          </figure>

          <div class="activity__card--text">

            <div class="activity__card--text-left">

              <span>
                <?= htmlspecialchars($creator['username']) ?>
              </span>

              <span>
                <?= $creator['total_items'] ?> Items
              </span>

            </div>

            <a
              href="<?= BASE_URL ?>page/creator.php?id=<?= $creator['account_id'] ?>"
              class="btn">
              View
            </a>

          </div>

        </li>

      <?php endwhile; ?>

    </ul>

  </div>

</section>