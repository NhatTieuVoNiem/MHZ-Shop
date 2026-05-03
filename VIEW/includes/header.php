<header class="header container">
    <a href="<?= BASE_URL ?>index.php" class="logo" aria-label="Trang chủ">
        <img src="<?= BASE_URL ?>assets/images/logo/Logo.png" alt="Game Shop Logo" />
    </a>

    <form class="search" role="search" method="get" action="<?= BASE_URL ?>search.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z" />
        </svg>
        <input
            type="search"
            name="q"
            placeholder="Search Here"
            aria-label="Tìm kiếm sản phẩm"
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
            autocomplete="off" />
    </form>

    <div class="header__list-icon">
        <div class="regime" role="group" aria-label="Chế độ giao diện">
            <button type="button" aria-label="Chế độ sáng">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z" />
                </svg>
            </button>
            <button type="button" aria-label="Chế độ tối">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 11.807A9.002 9.002 0 0 1 10.049 2a9.942 9.942 0 0 0-5.12 2.735c-3.905 3.905-3.905 10.237 0 14.142 3.906 3.906 10.237 3.905 14.143 0a9.946 9.946 0 0 0 2.735-5.119A9.003 9.003 0 0 1 12 11.807z" />
                </svg>
            </button>
        </div>

        <div class="notify-wrapper">
            <button type="button" class="notify" aria-label="Thông báo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586z" />
                </svg>

                <span class="badge">3</span>
            </button>

            <!-- dropdown -->
            <div class="notify-dropdown">
                <div class="empty">
                    <p>🔔 Không có thông báo</p>
                </div>
            </div>
        </div>
        <button type="button" class="user-btn" aria-label="Hồ sơ người dùng">

            <?php if (!empty($user['avatar'])): ?>
                <!-- có avatar -->
                <img
                    src="<?= BASE_URL . 'assets/images/avatar/' . $user['avatar'] ?>"
                    alt="Avatar người dùng">
            <?php else: ?>
                <!-- chưa có avatar -->
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2a10 10 0 0 0-10 10 10 10 0 0 0 4 7.92V20h.1a9.7 9.7 0 0 0 11.8 0h.1v-.08A10 10 0 0 0 22 12 10 10 0 0 0 12 2zm0 4a3.91 3.91 0 0 1 4 4 3.91 3.91 0 0 1-4 4 3.91 3.91 0 0 1-4-4 3.91 3.91 0 0 1 4-4zm0 14a8 8 0 0 1-5.9-2.6A5 5 0 0 1 11 15h2a5 5 0 0 1 4.9 2.4A8 8 0 0 1 12 20z" />
                </svg>
            <?php endif; ?>

        </button>
        <div class="auth-modal">
  <div class="auth-box">
    <h3>Xin chào 👋</h3>
    <p>Vui lòng đăng nhập để tiếp tục</p>

    <a href="<?= BASE_URL ?>page/login.php" class="btn login">Đăng nhập</a>
    <a href="<?= BASE_URL ?>page/register.php" class="btn register">Đăng ký</a>
  </div>
</div>
    </div>
</header>