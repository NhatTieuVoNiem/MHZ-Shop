<?php
/**
 * Trang Bảng xếp hạng / Dashboard thống kê
 * - Cột trái: thẻ Trending Bids (sản phẩm, lượt xem, lượt thích + % tăng trưởng)
 * - Cột giữa: biểu đồ đường giá ETH trung bình theo ngày
 * - Cột phải: biểu đồ donut tỷ lệ đơn đã bán / đã hủy
 * - Phía dưới: danh sách top sản phẩm theo lượt xem
 */

define('BASE_PATH', __DIR__);
define('BASE_URL', '../');
require_once("../../MODEL/connect.php");
require_once("../../MODEL/Product.php");

$productModel = new Product($conn);

/** Định dạng số lớn dạng rút gọn: 24000 → "24K", 1500000 → "1.5M */
function formatCompactNumber(int|float $value): string
{
    if ($value >= 1000000) {
        return round($value / 1000000, 1) . 'M';
    }
    if ($value >= 1000) {
        return round($value / 1000, 1) . 'K';
    }
    return (string) (int) $value;
}

/** Tính % thay đổi giữa kỳ hiện tại và kỳ trước (dùng cho mũi tên xanh/đỏ trên thẻ) */
function calcGrowthPercent(int $current, int $previous): float
{
    if ($previous <= 0) {
        return $current > 0 ? 100.0 : 0.0;
    }
    return (($current - $previous) / $previous) * 100;
}

/** Thực thi câu COUNT(*) và trả về số nguyên; lỗi SQL thì trả 0 */
function fetchCount(mysqli $conn, string $sql): int
{
    $result = $conn->query($sql);
    if (!$result) {
        return 0;
    }
    $row = $result->fetch_assoc();
    return (int) ($row['total'] ?? 0);
}

// ── Tổng tích lũy (hiển thị trên 3 thẻ Trending Bids) ─────────────────────
$totalProducts = fetchCount($conn, "SELECT COUNT(*) AS total FROM products");
$totalViews = fetchCount($conn, "SELECT COUNT(*) AS total FROM product_views");
$totalLikes = fetchCount($conn, "SELECT COUNT(*) AS total FROM product_likes");

// ── Số liệu tuần này vs tuần trước (để tính % tăng/giảm) ─────────────────
$productsThisWeek = fetchCount(
    $conn,
    "SELECT COUNT(*) AS total FROM products WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
);
$productsLastWeek = fetchCount(
    $conn,
    "SELECT COUNT(*) AS total FROM products
     WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
       AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)"
);

$viewsThisWeek = fetchCount(
    $conn,
    "SELECT COUNT(*) AS total FROM product_views WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
);
$viewsLastWeek = fetchCount(
    $conn,
    "SELECT COUNT(*) AS total FROM product_views
     WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
       AND viewed_at < DATE_SUB(NOW(), INTERVAL 7 DAY)"
);

$likesThisWeek = fetchCount(
    $conn,
    "SELECT COUNT(*) AS total FROM product_likes WHERE liked_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
);
$likesLastWeek = fetchCount(
    $conn,
    "SELECT COUNT(*) AS total FROM product_likes
     WHERE liked_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
       AND liked_at < DATE_SUB(NOW(), INTERVAL 7 DAY)"
);

/** Cấu hình 3 thẻ bên trái dashboard — mỗi phần tử render một .bid-card */
$trendingBids = [
    [
        'icon' => 'fa-wallet',
        'color' => 'purple',
        'value' => formatCompactNumber($totalProducts),
        'label' => 'Sản phẩm',
        'change' => calcGrowthPercent($productsThisWeek, $productsLastWeek),
    ],
    [
        'icon' => 'fa-eye',
        'color' => 'red',
        'value' => formatCompactNumber($totalViews),
        'label' => 'Lượt xem',
        'change' => calcGrowthPercent($viewsThisWeek, $viewsLastWeek),
    ],
    [
        'icon' => 'fa-heart',
        'color' => 'green',
        'value' => formatCompactNumber($totalLikes),
        'label' => 'Lượt thích',
        'change' => calcGrowthPercent($likesThisWeek, $likesLastWeek),
    ],
];

// ── Dữ liệu biểu đồ donut (Statistics) ───────────────────────────────────
$ordersSold = fetchCount(
    $conn,
    "SELECT COUNT(*) AS total FROM orders
     WHERE status IN ('completed', 'delivered', 'paid', 'success')"
);
$ordersCancelled = fetchCount(
    $conn,
    "SELECT COUNT(*) AS total FROM orders
     WHERE status IN ('cancelled', 'canceled', 'cancel')"
);
// Chưa có đơn hàng thì dùng tỷ lệ mẫu 75/25 để chart không trống
if ($ordersSold + $ordersCancelled <= 0) {
    $ordersSold = 75;
    $ordersCancelled = 25;
} elseif ($ordersCancelled <= 0) {
    $ordersCancelled = max(1, (int) round($ordersSold * 0.25));
}

// ── Dữ liệu biểu đồ đường ETH Price ──────────────────────────────────────
$ethLabels = [];
$ethValues = [];
$priceResult = $conn->query("
    SELECT DATE_FORMAT(created_at, '%d/%m') AS label, ROUND(AVG(price), 2) AS avg_price
    FROM products
    GROUP BY DATE(created_at)
    ORDER BY created_at ASC
    LIMIT 8
");

if ($priceResult && $priceResult->num_rows > 0) {
    while ($row = $priceResult->fetch_assoc()) {
        $ethLabels[] = $row['label'];
        $ethValues[] = (float) $row['avg_price'];
    }
} else {
    // Dữ liệu mặc định khi chưa có sản phẩm — Chart.js vẫn vẽ được
    $ethLabels = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN', 'T2'];
    $ethValues = [0, 100, 90, 150, 140, 200, 180, 210];
}

// Cấu hình trục Y truyền sang ranking.js qua data-max / data-step
$ethMax = max($ethValues) > 0 ? max($ethValues) : 350;
$ethStep = $ethMax <= 100 ? 20 : 50;
$ethAxisMax = (int) (ceil($ethMax / $ethStep) * $ethStep);
if ($ethAxisMax < 50) {
    $ethAxisMax = 50;
}

// Top 6 sản phẩm xem nhiều nhất (phần lưới phía dưới trang)
$trendingProducts = $productModel->getTrendingProducts(6);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bảng xếp hạng — MHZ Shop</title>
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

      <section class="ranking-page container">

        <div class="ranking-dashboard">

          <!-- Cột 1: Thẻ thống kê nhanh -->
          <div class="ranking-dashboard__col ranking-dashboard__col--bids">
            <h2 class="ranking-dashboard__title">Thống kê</h2>
            <div class="bid-cards">
              <?php foreach ($trendingBids as $bid): ?>
                <?php
                // Màu và dấu + cho % tăng, màu đỏ nếu giảm
                $isPositive = $bid['change'] >= 0;
                $changeClass = $isPositive ? 'bid-card__change--up' : 'bid-card__change--down';
                $changePrefix = $isPositive ? '+' : '';
                ?>
                <article class="bid-card">
                  <div class="bid-card__icon bid-card__icon--<?= $bid['color'] ?>">
                    <i class="fa-solid <?= $bid['icon'] ?>"></i>
                  </div>
                  <div class="bid-card__info">
                    <strong><?= htmlspecialchars($bid['value']) ?></strong>
                    <span><?= htmlspecialchars($bid['label']) ?></span>
                  </div>
                  <p class="bid-card__change <?= $changeClass ?>">
                    <?= $changePrefix . number_format($bid['change'], 3) ?>%
                  </p>
                </article>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Cột 2: Biểu đồ giá — dữ liệu đọc từ data-* bởi ranking.js -->
          <div class="ranking-dashboard__col ranking-dashboard__col--eth">
            <h2 class="ranking-dashboard__title">Biểu đồ giá</h2>
            <div class="chart-panel">
              <canvas
                id="ethPriceChart"
                aria-label="Biểu đồ giá ETH"
                data-labels='<?= htmlspecialchars(json_encode($ethLabels), ENT_QUOTES) ?>'
                data-values='<?= htmlspecialchars(json_encode($ethValues), ENT_QUOTES) ?>'
                data-max="<?= $ethAxisMax ?>"
                data-step="<?= $ethStep ?>">
              </canvas>
            </div>
          </div>

          <!-- Cột 3: Donut đơn hàng — data-sold / data-cancelled cho ranking.js -->
          <div class="ranking-dashboard__col ranking-dashboard__col--stats">
            <h2 class="ranking-dashboard__title">Tỉ lệ mua bán</h2>
            <div class="chart-panel chart-panel--donut">
              <div class="chart-panel__donut-wrap">
                <canvas
                  id="statsDonutChart"
                  aria-label="Thống kê đơn hàng"
                  data-sold="<?= $ordersSold ?>"
                  data-cancelled="<?= $ordersCancelled ?>">
                </canvas>
              </div>
              <ul class="stats-legend">
                <li>
                  <span class="stats-legend__dot stats-legend__dot--sold"></span>
                  Đã bán
                </li>
                <li>
                  <span class="stats-legend__dot stats-legend__dot--cancel"></span>
                  Đã hủy
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Danh sách sản phẩm xếp hạng theo lượt xem -->
        <div class="ranking-products">
          <div class="ranking-products__head">
            <h2>Top sản phẩm nổi bật</h2>
            <a href="<?= BASE_URL ?>page/products.php">>> Xem thêm</a>
          </div>

          <div class="ranking-products__grid">
            <?php if ($trendingProducts && $trendingProducts->num_rows > 0): ?>
              <?php $index = 0; ?>
              <?php while ($item = $trendingProducts->fetch_assoc()): ?>
                <?php $index++; ?>
                <article class="ranking-product-card">
                  <span class="ranking-product-card__rank">#<?= $index ?></span>
                  <img
                    src="<?= htmlspecialchars($item['thumbnail_url']) ?>"
                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                    onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">
                  <div class="ranking-product-card__body">
                    <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                    <p>
                      <i class="fa-solid fa-eye"></i>
                      <?= number_format((int) ($item['total_views'] ?? 0)) ?> lượt xem
                    </p>
                    <p>
                      <i class="fa-solid fa-gem"></i>
                      <?= htmlspecialchars($item['price']) ?> ETH
                    </p>
                  </div>
                  <a
                    href="productsDetails.php?id=<?= (int) $item['product_id'] ?>"
                    class="ranking-product-card__btn">
                    Xem chi tiết
                  </a>
                </article>
              <?php endwhile; ?>
            <?php else: ?>
              <p class="ranking-products__empty">Chưa có sản phẩm nào.</p>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <?php require '../includes/footer.php'; ?>
    </div>
  </div>

  <!-- Chart.js: khởi tạo biểu đồ trong ranking.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/ranking.js?v=<?= time() ?>"></script>
</body>

</html>
