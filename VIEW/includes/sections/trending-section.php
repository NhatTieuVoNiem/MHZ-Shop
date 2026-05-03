<?php
// Dữ liệu tĩnh — sau này thay bằng query DB
$trendingItems = [
  ['id' => 1, 'name' => 'Liquid Wave',  'image' => 'NFT(1).png',       'alt' => 'NFT Liquid Wave'],
  ['id' => 2, 'name' => 'Butterfly',    'image' => 'Butterfly.jpg',    'alt' => 'NFT Butterfly'],
  ['id' => 3, 'name' => 'Doomsday',     'image' => 'doomsday-terminator-granger-mobile-legends-ml-wallpaper-1280x960_20.jpg', 'alt' => 'NFT Doomsday'],
  ['id' => 4, 'name' => 'Moskov',       'image' => 'moskov-mobile-legends-ml-wallpaper-3840x2400_9.jpg', 'alt' => 'NFT Moskov'],
  ['id' => 5, 'name' => 'Nakroth',      'image' => 'nakroth.jpg',      'alt' => 'NFT Nakroth'],
  ['id' => 6, 'name' => 'Natalya',      'image' => 'natalya.jpg',      'alt' => 'NFT Natalya'],
  ['id' => 7, 'name' => 'Sephera',      'image' => 'sephera.jpg',      'alt' => 'NFT Sephera'],
  ['id' => 8, 'name' => 'Telannas',     'image' => 'telannas.jpg',     'alt' => 'NFT Telannas'],
];
?>

<section class="trending-section container" aria-label="Trending Bids">
  <div class="section__head">
    <h2 class="section__head--title">Trending Bids</h2>
    <nav class="section__head--menu" aria-label="Lọc danh mục">
      <button type="button" class="active">All</button>
      <button type="button">Artwork</button>
      <button type="button">Book</button>
    </nav>
  </div>

  <div class="section__content">
    <?php foreach ($trendingItems as $item): ?>
      <article class="section__content--card">
        <figure class="content__card--banner">
          <img
            src="<?= BASE_URL ?>assets/images/home/trendingSection/<?= htmlspecialchars($item['image']) ?>"
            alt="<?= htmlspecialchars($item['alt']) ?>"
          />
        </figure>
        <div class="content__card--info">
          <h3><?= htmlspecialchars($item['name']) ?></h3>
          <div class="fames">
            <div class="fames__line">
              <span>Auction time</span>
              <div class="fames__line--right">
                <span>Current Bid</span>
                <span class="color--pp">0.05 ETH</span>
              </div>
            </div>
            <div class="fames__line">
              <span>3h 1m 50s</span>
              <span>0.15 ETH</span>
            </div>
          </div>
          <a href="<?= BASE_URL ?>page/bid.php?id=<?= $item['id'] ?>" class="btn">Place a Bid</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>