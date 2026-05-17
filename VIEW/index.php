<?php
// Đường dẫn gốc (dùng khi include từ các trang con)
define('BASE_PATH', __DIR__);
define('BASE_URL', './');
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
  <link rel="stylesheet" href="<?= BASE_URL ?>style/home.css?v=<?= time() ?>" />
</head>

<body>
  <div class="wrapper">
    <?php require BASE_PATH . '/includes/nav-menu.php'; ?>
    <div class="content">
      <?php require BASE_PATH . '/includes/header.php'; ?>

      <main class="body">
      
        <?php require BASE_PATH . '/includes/sections/top-section.php'; ?>
        <?php require BASE_PATH . '/includes/sections/trending-section.php'; ?>
        <?php require BASE_PATH . '/includes/sections/recent-section.php'; ?>
      </main>

      <?php require BASE_PATH . '/includes/footer.php'; ?>
    </div>

  </div>
  <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/productsView.js?v=<?= time() ?>"></script>
</body>

</html>