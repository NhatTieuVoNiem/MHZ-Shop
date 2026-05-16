  <?php

  require_once("../MODEL/connect.php");
  require_once("../MODEL/Product.php");

  $productModel = new Product($conn);

  // lấy NFT nổi bật
  $featuredNFT = $productModel->getFeaturedNFT();

  ?>
  <section class="top-section container" aria-label="Banner chính">

    <div class="top-section__left">

      <h1 class="top-section__left--title">
        Website, Hosting, Bản quyền, ... mọi thứ trong tay bạn
      </h1>

      <p class="top-section__left--text">
        Hãy bắt đầu với việc tạo một sản phẩm mới
      </p>

      <div class="top-section__left--list-btn">
        <a href="<?= BASE_URL ?>page/ranking.php" class="btn">
          Khám phá
        </a>

        <a href="<?= BASE_URL ?>page/create.php" class="btn btn__view--red create-btn">
          Tạo mới
        </a>
      </div>

    </div>

    <div class="top-section__right">

      <figure class="top-section__right--banner">

        <img
          src="<?= htmlspecialchars($featuredNFT['thumbnail']) ?>"
          alt="<?= htmlspecialchars($featuredNFT['product_name']) ?>"
          onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'" />

      </figure>

      <div class="top-section__right--info">

        <div class="avatar">

          <img
            src="<?= htmlspecialchars($featuredNFT['avatar_url']) ?>"
            alt="Avatar <?= htmlspecialchars($featuredNFT['username']) ?>" />

          <h3>
            <?= htmlspecialchars($featuredNFT['username']) ?>
          </h3>

        </div>

        <div class="fames">

          <h2>
            <?= htmlspecialchars($featuredNFT['product_name']) ?>
          </h2>

          <p class="fames__line">

            <span>Ngày đăng</span>

            <span>
             Giá bán
            </span>

          </p>

          <p class="fames__line">

            <span>
              <?= htmlspecialchars($featuredNFT['created_at']) ?>
            </span>

            <span>
              <?= htmlspecialchars($featuredNFT['price']) ?> ETH
            </span>

          </p>

        </div>

        <div class="top-section__left--list-btn">

          <a
            href="<?= htmlspecialchars($featuredNFT['preview_url']) ?>"
            class="btn">
            Xem trước
          </a>

          <a 
            class="btn btn__view--red buy-btn">
            Đặt mua
          </a>


        </div>

      </div>

    </div>

  </section>