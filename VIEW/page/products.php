<?php
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');

session_start();

require_once("../../MODEL/connect.php");
require_once("../../MODEL/Category.php");
require_once("../../MODEL/Product.php");

$productModel  = new Product($conn);
$categoryModel = new Category($conn);

// Lấy tất cả danh mục
$categories = $categoryModel->getAll()->fetch_all(MYSQLI_ASSOC);

// Đọc filter từ GET
$keyword     = trim($_GET['keyword']  ?? '');
$selectedCat = $_GET['category'] ?? '';
$price       = $_GET['price']    ?? '';
$sort        = $_GET['sort']     ?? '';
$currentPage = max(1, (int)($_GET['page'] ?? 1));

// ── Xác định mode ──────────────────────────────────────────────
$hasFilter = $keyword !== '' || $price !== '' || $sort !== '';

if ($hasFilter || $selectedCat !== '') {
    // Nếu có filter (kể cả chỉ chọn danh mục trong form lọc)
    $mode = 'filter';
} elseif ($selectedCat !== '') {
    $mode = 'category';
} else {
    $mode = 'default';
}

// Phân biệt: bấm "Xem thêm" (chỉ có category, không có filter khác)
// vs chọn danh mục trong form lọc (có thể kèm keyword/price/sort)
if (!$hasFilter && $selectedCat !== '' && !isset($_GET['keyword']) && !isset($_GET['price']) && !isset($_GET['sort'])) {
    $mode = 'category';
} elseif ($hasFilter || $selectedCat !== '') {
    $mode = 'filter';
} else {
    $mode = 'default';
}

// ── Chế độ FILTER ──────────────────────────────────────────────
$itemsPerPage  = 4;
$totalItems    = 0;
$totalPages    = 1;
$pagedProducts = [];
$filterQuery   = '';

if ($mode === 'filter') {
    $allRows = $productModel->filterProducts($keyword, $selectedCat, $price, $sort)
                            ->fetch_all(MYSQLI_ASSOC);

    $totalItems  = count($allRows);
    $totalPages  = max(1, (int)ceil($totalItems / $itemsPerPage));
    $currentPage = min($currentPage, $totalPages);
    $offset      = ($currentPage - 1) * $itemsPerPage;
    $pagedProducts = array_slice($allRows, $offset, $itemsPerPage);

    $filterQuery = http_build_query(array_filter([
        'keyword'  => $keyword,
        'category' => $selectedCat,
        'price'    => $price,
        'sort'     => $sort,
    ]));
}

// ── Chế độ CATEGORY ────────────────────────────────────────────
$catItemsPerPage = 16;
$catTotalItems   = 0;
$catTotalPages   = 1;
$catProducts     = [];
$catName         = '';

if ($mode === 'category') {
    $catId = (int)$selectedCat;

    foreach ($categories as $cat) {
        if ((int)$cat['category_id'] === $catId) {
            $catName = $cat['category_name'];
            break;
        }
    }

    $catTotalItems = $productModel->countByCategory($catId);
    $catTotalPages = max(1, (int)ceil($catTotalItems / $catItemsPerPage));
    $currentPage   = min($currentPage, $catTotalPages);
    $catOffset     = ($currentPage - 1) * $catItemsPerPage;
    $catProducts   = $productModel->getByCategory($catId, $catItemsPerPage, $catOffset);

    $totalPages  = $catTotalPages;
    $filterQuery = 'category=' . $catId;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MHZ Shop</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>style/reset.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>style/font.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>style/common.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>style/menu.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>style/header.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>style/footer.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>style/nav-menu.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>style/products.css?v=<?= time() ?>" />
</head>
<body>
<div class="wrapper">
  <?php require '../includes/nav-menu.php'; ?>

  <div class="content">
    <div class="auth-modal">
      <div class="auth-box">
        <h3>Xin chào 👋</h3>
        <p>Vui lòng đăng nhập để tiếp tục</p>
        <a href="<?= BASE_URL ?>page/login.php" class="btn login">Đăng nhập</a>
        <a href="<?= BASE_URL ?>page/register.php" class="btn register">Đăng ký</a>
      </div>
    </div>

    <!-- Form lọc -->
    <form method="GET" action="products.php" class="filter-bar">
      <div class="filter-item">
        <input type="text" name="keyword" placeholder="Tìm sản phẩm..."
          class="filter-input" value="<?= htmlspecialchars($keyword) ?>">
      </div>
      <div class="filter-item">
        <select name="category" class="filter-select">
          <option value="">Danh mục</option>
          <?php foreach ($categories as $row): ?>
            <option value="<?= htmlspecialchars($row['category_id']) ?>"
              <?= ($selectedCat == $row['category_id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['category_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-item">
        <select name="price" class="filter-select">
          <option value="">Giá</option>
          <option value="under1" <?= ($price === 'under1') ? 'selected' : '' ?>>Dưới 1 triệu</option>
          <option value="1to10"  <?= ($price === '1to10')  ? 'selected' : '' ?>>1 - 10 triệu</option>
          <option value="over10" <?= ($price === 'over10') ? 'selected' : '' ?>>Trên 10 triệu</option>
        </select>
      </div>
      <div class="filter-item">
        <select name="sort" class="filter-select">
          <option value="">Sắp xếp</option>
          <option value="newest" <?= ($sort === 'newest') ? 'selected' : '' ?>>Mới nhất</option>
          <option value="asc"    <?= ($sort === 'asc')    ? 'selected' : '' ?>>Giá tăng dần</option>
          <option value="desc"   <?= ($sort === 'desc')   ? 'selected' : '' ?>>Giá giảm dần</option>
          <option value="views"  <?= ($sort === 'views')  ? 'selected' : '' ?>>Lượt xem</option>
          <option value="likes"  <?= ($sort === 'likes')  ? 'selected' : '' ?>>Lượt thích</option>
        </select>
      </div>
      <button type="submit" class="filter-btn">Lọc</button>
    </form>

    <?php if ($mode === 'filter'): ?>
    <!-- ── Kết quả lọc (4/trang) ─────────────────────────────── -->
      <section class="category-section container" aria-label="Kết quả lọc">
        <div class="section__head">
          <h2 class="section__head--title">
            Kết quả lọc
            <small style="font-size:14px; font-weight:normal; color:#888;">
              (<?= $totalItems ?> sản phẩm)
            </small>
          </h2>
        </div>
        <div class="section__content">
          <?php if (!empty($pagedProducts)): ?>
            <?php foreach ($pagedProducts as $item): ?>
              <?php include '../includes/product-card.php'; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Không tìm thấy sản phẩm phù hợp.</p>
          <?php endif; ?>
        </div>
        <?php if ($totalPages > 1): ?>
          <?php include '../includes/pagination.php'; ?>
        <?php endif; ?>
      </section>

    <?php elseif ($mode === 'category'): ?>
    <!-- ── Xem thêm theo danh mục (16/trang) ─────────────────── -->
      <section class="category-section container" aria-label="<?= htmlspecialchars($catName) ?>">
        <div class="section__head">
          <h2 class="section__head--title">
            <?= htmlspecialchars($catName) ?>
            <small style="font-size:14px; font-weight:normal; color:#888;">
              (<?= $catTotalItems ?> sản phẩm)
            </small>
          </h2>
          <a href="<?= BASE_URL ?>page/products.php" class="view-more">&laquo; Quay lại</a>
        </div>
        <div class="section__content">
          <?php if (!empty($catProducts)): ?>
            <?php foreach ($catProducts as $item): ?>
              <?php include '../includes/product-card.php'; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Không có sản phẩm nào trong danh mục này.</p>
          <?php endif; ?>
        </div>
        <?php if ($catTotalPages > 1): ?>
          <?php include '../includes/pagination.php'; ?>
        <?php endif; ?>
      </section>

    <?php else: ?>
    <!-- ── Trang mặc định ────────────────────────────────────── -->
      <?php foreach ($categories as $cat):
        $categoryId   = $cat['category_id'];
        $categoryName = $cat['category_name'];
        $products     = $productModel->getTopViewedProductsByCategory($categoryId, 4);
      ?>
        <section class="category-section container" aria-label="<?= htmlspecialchars($categoryName) ?>">
          <div class="section__head">
            <h2 class="section__head--title"><?= htmlspecialchars($categoryName) ?></h2>
            <a href="<?= BASE_URL ?>page/products.php?category=<?= (int)$categoryId ?>" class="view-more">&gt;&gt; Xem thêm</a>
          </div>
          <div class="section__content">
            <?php if ($products && $products->num_rows > 0): ?>
              <?php while ($item = $products->fetch_assoc()): ?>
                <?php include '../includes/product-card.php'; ?>
              <?php endwhile; ?>
            <?php else: ?>
              <p>Không có sản phẩm nào trong danh mục này.</p>
            <?php endif; ?>
          </div>
        </section>
      <?php endforeach; ?>

    <?php endif; ?>

    <?php require '../includes/footer.php'; ?>
  </div>
</div>
<script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
<script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
<script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
</body>
</html>