<?php
// Đường dẫn gốc (dùng khi include từ các trang con)
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');

session_start();

require_once "../../MODEL/connect.php";
require_once "../../CONTROLLER/controller_profile.php";

$profileController = new ControllerProfile($conn);

if (isset($_SESSION['account_id'])) {
  $account_id = $_SESSION['account_id'];
} else {
  $account_id = 0;
}

$data = $profileController->getProfileData($account_id);

$user = $data['user'];
$products = $data['products'];
$totalProducts = $data['totalProducts'];

$fullName =
  trim(
    ($user['last_name'] ?? '') . ' ' .
      ($user['middle_name'] ?? '') . ' ' .
      ($user['first_name'] ?? '')
  );
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
  <link rel="stylesheet" href="<?= BASE_URL ?>style/profile.css?v=<?= time() ?>" />
   <link rel="stylesheet" href="<?= BASE_URL ?>style/products.css?v=<?= time() ?>" />
</head>

<body>
  <div class="wrapper">
    <?php
    if (isset($_SESSION['account_id'])) {
      require '../includes/nav_menu_login.php';
    } else {
      require '../includes/nav-menu.php';
    }
    ?>
    <div class="content">
      <?php require '../includes/header.php'; ?>

      <?php require '../includes/profile.php'; ?>

      <?php require '../includes/footer.php'; ?>
    </div>

  </div>
  <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
</body>

</html>