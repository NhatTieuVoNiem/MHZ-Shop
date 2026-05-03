<?php
$recentActivities = [
  ['image' => 'animakid.png',   'name' => 'Papaya', 'action' => 'Purchase by you for 0.05 ETH'],
  ['image' => 'BlueWhale.png',  'name' => 'Papaya', 'action' => '0.06 ETH Received'],
  ['image' => 'DigiLab.png',    'name' => 'Papaya', 'action' => 'Started Following you'],
  ['image' => 'Dotgu.png',      'name' => 'Papaya', 'action' => 'Has been sold for 12.75 ETH'],
  ['image' => 'GravityOne.png', 'name' => 'Papaya', 'action' => 'Purchase by you for 0.05 ETH'],
];

$topCreators = [
  ['id' => 1, 'image' => 'GravityOne.png', 'name' => 'GravityOne', 'items' => 60],
  ['id' => 2, 'image' => 'Ghiblier.png',   'name' => 'Ghiblier',   'items' => 60],
  ['id' => 3, 'image' => 'Juanie.png',      'name' => 'Juanie',     'items' => 60],
  ['id' => 4, 'image' => 'Shroomie.png',    'name' => 'Shroomie',   'items' => 60],
  ['id' => 5, 'image' => 'RustyRobot.png',  'name' => 'RustyRobot', 'items' => 60],
  ['id' => 6, 'image' => 'mr fox.png',      'name' => 'Mr Fox',     'items' => 60],
  ['id' => 7, 'image' => 'Juanie.png',      'name' => 'Juanie',     'items' => 60],
  ['id' => 8, 'image' => 'RustyRobot.png',  'name' => 'RustyRobot', 'items' => 60],
];
?>

<section class="recent-section container" aria-label="Hoạt động gần đây">

  <div class="activity">
    <div class="activity__head">
      <h3 class="bids__title">Recent Activity</h3>
      <a href="<?= BASE_URL ?>page/bid.php">See More</a>
    </div>
    <ul class="activity__body">
      <?php foreach ($recentActivities as $act): ?>
        <li class="activity__card">
          <figure class="activity__card--image">
            <img
              src="<?= BASE_URL ?>assets/images/home/recentSection/<?= htmlspecialchars($act['image']) ?>"
              alt="<?= htmlspecialchars($act['name']) ?>"
            />
          </figure>
          <div class="activity__card--text">
            <div class="activity__card--text-left">
              <span><?= htmlspecialchars($act['name']) ?></span>
              <span><?= htmlspecialchars($act['action']) ?></span>
            </div>
            <time class="activity__card--text-right" datetime="PT12M">12 mins ago</time>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="creator">
    <div class="creator__head">
      <h3 class="bids__title">Top Creators</h3>
    </div>
    <ul class="creator__body">
      <?php foreach ($topCreators as $creator): ?>
        <li class="activity__card">
          <figure class="activity__card--image">
            <img
              src="<?= BASE_URL ?>assets/images/home/recentSection/<?= htmlspecialchars($creator['image']) ?>"
              alt="Creator <?= htmlspecialchars($creator['name']) ?>"
            />
          </figure>
          <div class="activity__card--text">
            <div class="activity__card--text-left">
              <span><?= htmlspecialchars($creator['name']) ?></span>
              <span><?= $creator['items'] ?> Items</span>
            </div>
            <a href="<?= BASE_URL ?>page/creator.php?id=<?= $creator['id'] ?>" class="btn">Follow</a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

</section>