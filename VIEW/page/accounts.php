<?php
// Đường dẫn gốc (dùng khi include từ các trang con)
define('BASE_PATH', __DIR__);
define('BASE_URL', '../');
session_start();

if (
    !isset($_SESSION['account_id']) ||
    $_SESSION['role_id'] != 1
) {
    header("Location: login.php");
    exit();
}

require_once("../../MODEL/connect.php");
require_once '../../CONTROLLER/controller_account.php';

$accountController = new controller_account($conn);

$data = $accountController->dashboardData();

$topSeller = $data['topSeller'];
$topBuyer = $data['topBuyer'];

$resultTopSellers = $data['topSellers'];
$resultTopBuyers = $data['topBuyers'];

$totalSeller = $data['totalSeller'];
$totalBuyer = $data['totalBuyer'];
$deletedAccounts = $data['deletedAccounts'];
$deletedTotal    = $data['deletedTotal'];
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
    <link rel="stylesheet" href="<?= BASE_URL ?>style/footer.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="<?= BASE_URL ?>style/accounts.css?v=<?= time() ?>" />
</head>

<body>
    <div class="wrapper">
        <div class="admin-header">

            <a href="./admin.php" class="logo">
                <h2>MHZ Admin</h2>
            </a>

            <nav class="admin-nav">
                <a href="admin.php" class="active">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>

                <a href="accounts.php">
                    <i class="fas fa-users"></i>
                    Tài khoản
                </a>

                <a href="products-admin.php">
                    <i class="fas fa-gamepad"></i>
                    Sản phẩm
                </a>

                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i>
                    Đơn hàng
                </a>

                <a href="reports.php">
                    <i class="fas fa-chart-line"></i>
                    Báo cáo
                </a>
            </nav>

            <div class="admin-user">

                <?php if (!empty($user['avatar'])): ?>
                    <img
                        src="<?= BASE_URL . 'assets/images/avatar/' . $_SESSION['avatar_url'] ?>"
                        alt="Avatar">
                <?php else: ?>
                    <img
                        src="<?= BASE_URL ?>assets/images/avatar/avatar.png"
                        alt="Avatar">
                <?php endif; ?>

                <div class="user-info">
                    <span class="name">
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
                    </span>
                    <small>Administrator</small>
                </div>

                <a href="logout.php" class="logout-btn">
                    Đăng xuất
                </a>

            </div>

        </div>
        <div class="top_accounts">

            <!-- Người bán doanh thu cao nhất -->
            <a href="accounts_profile.php?id=<?= $topSeller['account_id'] ?>" class="cart">
                <div class="avatar">
                    <img src="<?= !empty($topSeller['avatar_url']) ? $topSeller['avatar_url'] : '../assets/images/avatar/avatar.png' ?>" alt="">
                </div>

                <div class="info">
                    <span class="label">🏆 Người bán xuất sắc</span>
                    <h3><?= htmlspecialchars($topSeller['username']) ?></h3>
                    <p>
                        Doanh thu:
                        <?= number_format($topSeller['revenue'], 0, ',', '.') ?> VNĐ
                    </p>
                </div>
            </a>

            <!-- Người mua nhiều nhất -->
            <a href="accounts_profile.php?id=<?= $topBuyer['account_id'] ?>" class="cart">
                <div class="avatar">
                    <img src="<?= !empty($topBuyer['avatar_url']) ? $topBuyer['avatar_url'] : '../assets/images/avatar/avatar.png' ?>" alt="">
                </div>

                <div class="info">
                    <span class="label">🛒 Khách hàng VIP</span>
                    <h3><?= htmlspecialchars($topBuyer['username']) ?></h3>
                    <p>
                        Đã mua:
                        <?= number_format($topBuyer['total_products']) ?>
                        sản phẩm
                    </p>
                </div>
            </a>

        </div>
        <div class="list_seller">

            <div class="title_box">
                <h2>🏆 Top Người Bán Doanh Thu Cao Nhất</h2>

                <?php if ($totalSeller > 10): ?>
                    <a href="seller.php" class="view_more">
                        Xem tất cả →
                    </a>
                <?php endif; ?>
            </div>

            <div class="seller_list">

                <?php
                $rank = 1;

                while ($seller = mysqli_fetch_assoc($resultTopSellers)):
                ?>
                    <div class="seller_card">

                        <span class="rank">
                            #<?= $rank++ ?>
                        </span>

                        <img
                            src="<?= !empty($seller['avatar_url'])
                                        ? $seller['avatar_url']
                                        : '../assets/images/avatar/avatar.png' ?>"
                            alt="">

                        <div class="seller_info">
                            <a href="accounts_profile.php?id=<?= $seller['account_id'] ?>" class="seller_name">
                                <h3><?= htmlspecialchars($seller['username']) ?></h3>
                            </a>

                            <p>
                                <?= number_format($seller['total_products']) ?>
                                sản phẩm
                            </p>

                            <span class="revenue">
                                <?= number_format($seller['revenue'], 0, ',', '.') ?>
                                VNĐ
                            </span>
                        </div>
                        <div class="seller_actions">
                            <button
                                class="btn-action btn-edit openEditModal"
                                data-id="<?= $seller['account_id'] ?>">
                                ✏️ Sửa
                            </button>

                            <a href="accounts_delete.php?id=<?= $seller['account_id'] ?>"
                                class="btn-action btn-delete"
                                onclick="return confirm('Bạn có chắc muốn xóa người bán này?')">
                                🗑 Xóa
                            </a>
                        </div>


                    </div>
                <?php endwhile; ?>

            </div>

        </div>
        <div class="list_buyer">

            <div class="title_box">
                <h2>🛒 Top Người Mua Nhiều Nhất</h2>

                <?php if ($totalBuyer > 10): ?>
                    <a href="buyers.php" class="view_more">
                        Xem tất cả →
                    </a>
                <?php endif; ?>
            </div>

            <div class="buyer_list">

                <?php
                $rank = 1;

                while ($buyer = mysqli_fetch_assoc($resultTopBuyers)):
                ?>
                    <div class="buyer_card">

                        <span class="rank">
                            <?php
                            if ($rank == 1) echo "🥇";
                            elseif ($rank == 2) echo "🥈";
                            elseif ($rank == 3) echo "🥉";
                            else echo "#" . $rank;
                            ?>
                        </span>

                        <img
                            src="<?= !empty($buyer['avatar_url'])
                                        ? $buyer['avatar_url']
                                        : '../assets/images/avatar/avatar.png' ?>"
                            alt="">

                        <div class="buyer_info">
                            <a href="accounts_profile.php?id=<?= $buyer['account_id'] ?>" class="buyer_name">
                                <h3><?= htmlspecialchars($buyer['username']) ?></h3>
                            </a>

                            <p>
                                <?= number_format($buyer['total_orders']) ?>
                                đơn hàng
                            </p>

                            <span class="total_products">
                                <?= number_format($buyer['total_products']) ?>
                                sản phẩm đã mua
                            </span>

                            <span class="total_spent">
                                <?= number_format($buyer['total_spent'], 0, ',', '.') ?>
                                VNĐ
                            </span>
                        </div>

                        <div class="buyer_actions">

                            <button
                                class="btn-action btn-edit openEditModal"
                                data-id="<?= $buyer['account_id']?>">
                                ✏️ Sửa
                            </button>

                            <a href="accounts_delete.php?id=<?= $buyer['account_id'] ?>"
                                class="btn-action btn-delete"
                                onclick="return confirm('Bạn có chắc muốn xóa người mua này?')">
                                🗑 Xóa
                            </a>

                        </div>

                    </div>
                <?php
                    $rank++;
                endwhile;
                ?>

            </div>

        </div>
        <div class="list_deleted">

            <div class="title_box">
                <h2>🚫 Tài Khoản Đã Vô Hiệu Hóa</h2>

                <span class="deleted_count">
                    Tổng: <?= $deletedTotal ?>
                </span>
            </div>

            <div class="deleted_list">

                <?php while ($account = mysqli_fetch_assoc($deletedAccounts)): ?>

                    <div class="deleted_card">

                        <img
                            src="<?= !empty($account['avatar_url'])
                                        ? $account['avatar_url']
                                        : '../assets/images/avatar/avatar.png' ?>"
                            alt="">

                        <div class="deleted_info">

                            <h3>
                                <?= htmlspecialchars($account['username']) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars($account['email']) ?>
                            </p>

                            <span>
                                ID: <?= $account['account_id'] ?>
                            </span>

                        </div>

                        <div class="deleted_actions">

                            <a
                                href="accounts_restore.php?id=<?= $account['account_id'] ?>"
                                class="btn-action btn-restore"
                                onclick="return confirm('Khôi phục tài khoản này?')">
                                ♻️ Khôi phục
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        </div>
        <?php require '../includes/footer.php'; ?>
    </div>
    <div id="editModal" class="modal">

        <div class="modal-content">

            <span class="close-modal">&times;</span>

            <h2>Cập Nhật Tài Khoản</h2>

            <form id="editAccountForm" method="POST"
                action="../../CONTROLLER/controller_account.php">

                <!-- Account -->
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="account_id" id="account_id">
                <input type="hidden" name="profile_id" id="profile_id">

                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text"
                        name="username"
                        id="username"
                        required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                        name="email"
                        id="email"
                        required>
                </div>

                <div class="form-group">
                    <label>Mật khẩu mới</label>
                    <input type="password"
                        name="password"
                        id="password">
                    <small>Để trống nếu không đổi mật khẩu</small>
                </div>

                <div class="form-group">
                    <label>Vai trò</label>
                    <select name="role_id" id="role_id">
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                        <option value="3">Seller</option>
                    </select>
                </div>

                <hr>

                <!-- Profile -->

                <div class="form-group">
                    <label>Họ</label>
                    <input type="text"
                        name="last_name"
                        id="last_name">
                </div>

                <div class="form-group">
                    <label>Tên đệm</label>
                    <input type="text"
                        name="middle_name"
                        id="middle_name">
                </div>

                <div class="form-group">
                    <label>Tên</label>
                    <input type="text"
                        name="first_name"
                        id="first_name">
                </div>

                <div class="form-group">
                    <label>Giới tính</label>
                    <select name="gender_id" id="gender_id">
                        <option value="">-- Chọn --</option>
                        <option value="1">Nam</option>
                        <option value="2">Nữ</option>
                        <option value="3">Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ngày sinh</label>
                    <input type="date"
                        name="date_of_birth"
                        id="date_of_birth">
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text"
                        name="phone"
                        id="phone">
                </div>

                <div class="form-group">
                    <label>Tiểu sử</label>
                    <textarea name="bio"
                        id="bio"
                        rows="4"></textarea>
                </div>

                <button type="submit" class="btn-save">
                    💾 Lưu Thay Đổi
                </button>

            </form>

        </div>

    </div>

    <script src="../js/acounts.js?v=<?= time() ?>"></script>
</body>

</html>