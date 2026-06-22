<?php
session_start();
// Đường dẫn gốc (dùng khi include từ các trang con)
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');
require_once '../../MODEL/connect.php';
require_once '../../MODEL/Cart.php';
require_once '../../MODEL/CartItem.php';
require_once '../../MODEL/Account.php';

$cartModel = new Cart($conn);
$cartItemModel = new CartItem($conn);


$accountInfo = null;
$cartItems = [];
$total = 0;
$fullName = '';

if ($accountInfo) {

  $fullName =
    trim(
      ($accountInfo['last_name'] ?? '') . ' ' .
        ($accountInfo['middle_name'] ?? '') . ' ' .
        ($accountInfo['first_name'] ?? '')
    );
}
if (isset($_SESSION['account_id'])) {

  $accountModel = new Account($conn);

  $accountInfo = $accountModel->getFullInfo(
    $_SESSION['account_id']
  );
}

if (isset($_SESSION['account_id'])) {

  $cart = $cartModel->getByAccountId(
    $_SESSION['account_id']
  );

  if ($cart) {

    $result = $cartItemModel->getByCartId(
      $cart['cart_id']
    );

    while ($row = $result->fetch_assoc()) {

      $row['subtotal'] =
        $row['price'] * $row['quantity'];

      $total += $row['subtotal'];

      $cartItems[] = $row;
    }
  }
}
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
  <link rel="stylesheet" href="<?= BASE_URL ?>style/cart.css?v=<?= time() ?>" />
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

      <main class="body">
        <section class="cart-page container">
          <div class="cart-header">
            <h1>🛒 Giỏ hàng của bạn</h1>
            <span>
              <?= count($cartItems) ?> sản phẩm
            </span>
          </div>

          <div class="cart-layout">

            <!-- Danh sách sản phẩm -->
            <div class="cart-products">

              <?php if (!empty($cartItems)): ?>

                <?php foreach ($cartItems as $item): ?>

                  <article class="cart-item">

                    <img
                      src="<?= BASE_URL . htmlspecialchars($item['thumbnail_url']) ?>"
                      alt="<?= htmlspecialchars($item['product_name']) ?>"
                      onerror="this.src='<?= BASE_URL ?>assets/images/Copilot_20260504_143121.png'">

                    <div class="cart-item-info">

                      <h3>
                        <a href="productsDetails.php?id=<?= $item['product_id'] ?>" class="product-link">
                          <?= htmlspecialchars($item['product_name']) ?>
                        </a>
                      </h3>

                      <span class="price">
                        <?= number_format($item['price'], 0, ',', '.') ?>đ
                      </span>

                    </div>

                    <div class="cart-quantity">
                      <a
                        href="../../CONTROLLER/controller_cart_update.php?action=decrease&id=<?= $item['cart_item_id'] ?>"
                        class="qty-btn">-</a>

                      <span><?= $item['quantity'] ?></span>

                      <a
                        href="../../CONTROLLER/controller_cart_update.php?action=increase&id=<?= $item['cart_item_id'] ?>"
                        class="qty-btn">+</a>
                    </div>

                    <div class="cart-total">
                      <?= number_format($item['subtotal'], 0, ',', '.') ?>đ
                    </div>

                    <a
                      href="../../CONTROLLER/controller_cart_delete.php?id=<?= $item['cart_item_id'] ?>"
                      class="cart-remove"
                      onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')">
                      <i class="fa-solid fa-trash"></i>
                    </a>

                  </article>

                <?php endforeach; ?>

              <?php else: ?>

                <div class="empty-cart">
                  <i class="fa-solid fa-cart-shopping"></i>
                  <h3>Giỏ hàng đang trống</h3>
                </div>

              <?php endif; ?>

            </div>

            <!-- Tổng thanh toán -->
            <aside class="cart-summary">

              <h2>Tóm tắt đơn hàng</h2>

              <div class="summary-row">
                <span>Tạm tính</span>
                <strong>
                  <?= number_format($total, 0, ',', '.') ?>đ
                </strong>
              </div>

              <div class="summary-row total">
                <span>Tổng cộng</span>
                <strong>
                  <?= number_format($total, 0, ',', '.') ?>đ
                </strong>
              </div>

              <button class="btn-checkout" id="openCheckout">
                Thanh toán ngay
              </button>

              <a href="products.php" class="btn-continue">
                Tiếp tục mua sắm
              </a>

            </aside>

          </div>
        </section>
      </main>

      <?php require '../includes/footer.php'; ?>
    </div>

  </div>
  <div class="checkout-modal" id="checkoutModal">

    <div class="checkout-box">

      <button class="close-modal" id="closeCheckout">
        &times;
      </button>

      <h2>Thanh toán đơn hàng</h2>

      <div class="checkout-total">
        Tổng tiền:
        <strong>
          <?= number_format($total, 0, ',', '.') ?>đ
        </strong>
      </div>

      <form action="../../CONTROLLER/controller_checkout.php" method="POST">

        <div class="form-group">
          <label>Họ và tên</label>
          <input
            type="text"
            name="fullname"
            value="<?= htmlspecialchars($fullName) ?>"
            required>
        </div>

        <div class="form-group">
          <label>Số điện thoại</label>
          <input
            type="text"
            name="phone"
            value="<?= htmlspecialchars($accountInfo['phone'] ?? '') ?>"
            required>
        </div>
        <div class="form-group">
          <label>Phương thức thanh toán</label>

          <select name="payment_method">
            <option value="cod">Thanh toán khi nhận hàng</option>
            <option value="banking">Chuyển khoản</option>
            <option value="momo">Ví MoMo</option>
          </select>
        </div>

        <button type="submit" class="btn-confirm">
          Xác nhận thanh toán
        </button>

      </form>

    </div>

  </div>
  <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
  <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
  <?php if (!isset($_SESSION['account_id'])): ?>
    <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
  <?php endif; ?>
  <script src="<?= BASE_URL ?>js/clickCheckOut.js?v=<?= time() ?>"></script>
</body>

</html>