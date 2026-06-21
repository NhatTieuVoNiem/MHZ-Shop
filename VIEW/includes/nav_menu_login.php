<nav class="nav-menu" aria-label="Sidebar navigation">
    <a href="./user.php" class="logo">
        <img src="<?= BASE_URL ?>assets/images/logo/Logo.png" alt="Game Shop Logo">
    </a>

    <ul class="menu">

        <li>
            <a href="./user.php">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                </svg>
                <span class="nav-label">Trang chủ</span>
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>page/products.php">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16zm-9 3.08-6-3.43V9.35l6 3.43v6.3zm1-8.16L7.08 7.5 12 4.92l4.92 2.58L13 10.92zm6 4.73-6 3.43v-6.3l6-3.43v6.3z" />
                </svg>
                <span class="nav-label">Sản phẩm</span>
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>page/cart.php">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-9.8-3h11.2c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0 0 22 6H5.21L4.27 4H1v2h2l3.6 7.59L5.25 16c-.16.28-.25.61-.25.95C5 18.1 6.9 19 7 19h13v-2H7.42a.13.13 0 0 1-.12-.13l.03-.14.9-1.73z" />
                </svg>
                <span class="nav-label">Giỏ hàng</span>
            </a>
        </li>

        <!-- Đơn hàng của tôi -->
        <li>
            <a href="<?= BASE_URL ?>page/orders.php">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14H7v-2h3v2zm7-4H7v-2h10v2zm0-4H7V7h10v2z" />
                </svg>
                <span class="nav-label">Đơn hàng</span>
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>page/ranking.php">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M7 20h2V8H7v12zm4 0h2V4h-2v16zm4 0h2v-8h-2v8z" />
                </svg>
                <span class="nav-label">Bảng xếp hạng</span>
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>page/profile.php">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2A10.13 10.13 0 0 0 2 12a10 10 0 0 0 4 7.92V20h.1a9.7 9.7 0 0 0 11.8 0h.1v-.08A10 10 0 0 0 22 12 10.13 10.13 0 0 0 12 2zM8.07 18.93A3 3 0 0 1 11 16.57h2a3 3 0 0 1 2.93 2.36 7.75 7.75 0 0 1-7.86 0zm9.54-1.29A5 5 0 0 0 13 14.57h-2a5 5 0 0 0-4.61 3.07A8 8 0 0 1 4 12a8.1 8.1 0 0 1 8-8 8.1 8.1 0 0 1 8 8 8 8 0 0 1-2.39 5.64z" />
                    <path d="M12 6a3.91 3.91 0 0 0-4 4 3.91 3.91 0 0 0 4 4 3.91 3.91 0 0 0 4-4 3.91 3.91 0 0 0-4-4zm0 6a1.91 1.91 0 0 1-2-2 1.91 1.91 0 0 1 2-2 1.91 1.91 0 0 1 2 2 1.91 1.91 0 0 1-2 2z" />
                </svg>
                <span class="nav-label">Hồ sơ cá nhân</span>
            </a>
        </li>

    </ul>


    <!-- Đăng xuất -->
    <a href="./logout.php" class="user">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M16 17v-3H8v-4h8V7l5 5-5 5z" />
            <path d="M3 3h9v2H5v14h7v2H3z" />
        </svg>
        <span class="nav-label">Đăng xuất</span>
    </a>

</nav>