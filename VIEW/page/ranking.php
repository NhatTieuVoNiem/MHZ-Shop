<?php
// Đường dẫn gốc (dùng khi include từ các trang con)
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');
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
  <link rel="stylesheet" href="<?= BASE_URL ?>style/products.css?v=<?= time() ?>" />
</head>

<body>
  <div class="wrapper">
    <?php require '../includes/nav-menu.php'; ?>
    <div class="content">
  
<?php require '../includes/header.php'; ?>

      <?php require '../includes/footer.php'; ?>
    </div>

  </div>
   <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
</body>

</html>