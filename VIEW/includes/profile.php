<?php

$user = $user ?? [];
$products = $products ?? null;
$totalProducts = $totalProducts ?? 0;

$fullName = trim(
    ($user['last_name'] ?? '') . ' ' .
        ($user['middle_name'] ?? '') . ' ' .
        ($user['first_name'] ?? '')
);

if ($fullName === '') {
    $fullName = $user['username'] ?? 'Người dùng MHZ Shop';
}

$avatar = !empty($user['avatar_url'])
    ? $user['avatar_url']
    : BASE_URL . 'assets/images/avatar/avatar.png';
?>

<div class="profile-card">

    <div class="profile-banner"></div>

    <div class="profile-content">

        <div class="profile-top">

            <div class="profile-left">

                <img
                    src="<?= htmlspecialchars($avatar) ?>"
                    alt="<?= htmlspecialchars($fullName) ?>"
                    class="profile-avatar"
                    onerror="this.src='<?= BASE_URL ?>assets/images/avatar/avatar.png'">

                <div class="profile-info">

                    <h1><?= htmlspecialchars($fullName) ?></h1>

                    <div class="profile-username">
                        @<?= htmlspecialchars($user['username'] ?? 'guest') ?>
                    </div>

                    <div class="stats">

                        <div class="stat-item">
                            <strong><?= (int)$totalProducts ?></strong>
                            <span>Sản phẩm</span>
                        </div>

                        <div class="stat-item">
                            <strong>
                                <?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật') ?>
                            </strong>
                            <span>Email</span>
                        </div>

                        <div class="stat-item">
                            <strong>
                                <?= htmlspecialchars($user['phone'] ?? '---') ?>
                            </strong>
                            <span>Điện thoại</span>
                        </div>

                    </div>

                    <p class="bio">
                        <?= htmlspecialchars(
                            $user['bio']
                                ?? 'Người dùng chưa cập nhật thông tin cá nhân.'
                        ) ?>
                    </p>

                </div>

            </div>

            <?php if (!empty($user)) : ?>
                <div class="profile-actions">
                    <button type="button" class="edit-btn">
                        <i class="fa-solid fa-pen"></i>
                        Chỉnh sửa hồ sơ
                    </button>
                </div>
            <?php endif; ?>

        </div>

    </div>

</div>

<!-- Danh sách sản phẩm -->

<div class="product-section">

    <div class="product-tabs">
        <button type="button" class="active">
            Đã đăng
            <span><?= (int)$totalProducts ?></span>
        </button>
    </div>

    <div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $item): ?>
                <?php include __DIR__ . '/product-card.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-product">
                <p>Người dùng chưa đăng sản phẩm nào.</p>
            </div>
        <?php endif; ?>
    </div>

</div>