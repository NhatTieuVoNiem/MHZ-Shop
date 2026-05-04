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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/login.css?v=<?= time() ?>" />
</head>

<body>
   <?php require BASE_PATH . '/../includes/nav-menu.php'; ?>
        <div class="login-container">
            <h2>Đăng nhập tài khoản NFT</h2>
            <p>Vui lòng đăng nhập để tiếp tục</p>

            <!-- Form gửi dữ liệu đến PHP -->
            <form action="../../CONTROLLER/controller_login.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                </div>
                <button type="submit" class="login-btn">Đăng nhập</button>
            </form>

            <div class="social-login">
                <p>hoặc đăng nhập bằng</p>
                <button>Google</button>
                <button>Facebook</button>
            </div>

            <div class="register-link">
                <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
            </div>
        </div>

</body>

</html>