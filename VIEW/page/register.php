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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/register.css?v=<?= time() ?>" />
</head>

<body>
    <?php require BASE_PATH . '/../includes/nav-menu.php'; ?>
    <div class="register-box">

        <h1>Đăng ký NFT</h1>
        <p>Tạo tài khoản để bắt đầu trải nghiệm</p>

        <form action="../../CONTROLLER/controller_register.php" method="POST">

            <div class="input-box">
                <input type="text" name="username" placeholder="Tên người dùng" required>
            </div>

            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Mật khẩu" required>
            </div>

            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn" name="btn_register">
                Đăng ký
            </button>

        </form>

        <div class="or">hoặc đăng ký bằng</div>

        <div class="social-login">
            <button>Google</button>
            <button>Facebook</button>
        </div>

        <div class="bottom-text">
            Đã có tài khoản?
            <a href="./login.php">Đăng nhập</a>
        </div>

    </div>
    <script src="<?= BASE_URL ?>js/theme.js?v=<?= time() ?>"></script>
    <script src="<?= BASE_URL ?>js/toggle.js?v=<?= time() ?>"></script>
    <script src="<?= BASE_URL ?>js/clickLogin.js?v=<?= time() ?>"></script>
    <script src="<?= BASE_URL ?>js/productsView.js?v=<?= time() ?>"></script>
</body>

</html>