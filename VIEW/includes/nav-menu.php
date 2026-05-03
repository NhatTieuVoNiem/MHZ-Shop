<nav class="nav-menu" aria-label="Sidebar navigation">
    <a href="<?= BASE_URL ?>index.php" class="logo" aria-label="Trang chủ">
        <img src="<?= BASE_URL ?>assets/images/logo/Logo.png" alt="Game Shop Logo" />
    </a>

  <ul class="menu" role="menubar">

  <li role="none">
    <a href="<?= BASE_URL ?>index.php" role="menuitem" aria-label="Trang chủ">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
        </svg>
      <span class="nav-label">Trang chủ</span>
    </a>
  </li>

  <li role="none">
    <a href="<?= BASE_URL ?>page/products.php" role="menuitem" aria-label="Sản phẩm">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16zm-9 3.08-6-3.43V9.35l6 3.43v6.3zm1-8.16L7.08 7.5 12 4.92l4.92 2.58L13 10.92zm6 4.73-6 3.43v-6.3l6-3.43v6.3z"/>
        </svg>
      <span class="nav-label">Sản phẩm</span>
    </a>
  </li>

  <li role="none">
    <a href="<?= BASE_URL ?>page/cart.php" role="menuitem" aria-label="Giỏ hàng">
       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-9.8-3h11.2c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0 0 22 6H5.21L4.27 4H1v2h2l3.6 7.59L5.25 16c-.16.28-.25.61-.25.95C5 18.1 6.9 19 7 19h13v-2H7.42a.13.13 0 0 1-.12-.13l.03-.14.9-1.73z"/>
        </svg>
      <span class="nav-label">Giỏ hàng</span>
    </a>
  </li>

  <li role="none">
    <a href="<?= BASE_URL ?>page/ranking.php" role="menuitem" aria-label="Bảng xếp hạng">
       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M7 20h2V8H7v12zm4 0h2V4h-2v16zm4 0h2v-8h-2v8z"/>
        </svg>
      <span class="nav-label">Bảng xếp hạng</span>
    </a>
  </li>

  <li role="none">
    <a href="<?= BASE_URL ?>page/profile.php" role="menuitem" aria-label="Thông tin cá nhân">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 2A10.13 10.13 0 0 0 2 12a10 10 0 0 0 4 7.92V20h.1a9.7 9.7 0 0 0 11.8 0h.1v-.08A10 10 0 0 0 22 12 10.13 10.13 0 0 0 12 2zM8.07 18.93A3 3 0 0 1 11 16.57h2a3 3 0 0 1 2.93 2.36 7.75 7.75 0 0 1-7.86 0zm9.54-1.29A5 5 0 0 0 13 14.57h-2a5 5 0 0 0-4.61 3.07A8 8 0 0 1 4 12a8.1 8.1 0 0 1 8-8 8.1 8.1 0 0 1 8 8 8 8 0 0 1-2.39 5.64z"/>
        <path d="M12 6a3.91 3.91 0 0 0-4 4 3.91 3.91 0 0 0 4 4 3.91 3.91 0 0 0 4-4 3.91 3.91 0 0 0-4-4zm0 6a1.91 1.91 0 0 1-2-2 1.91 1.91 0 0 1 2-2 1.91 1.91 0 0 1 2 2 1.91 1.91 0 0 1-2 2z"/>
      </svg>
      <span class="nav-label">Tài khoản</span>
    </a>
  </li>

</ul>

<a href="<?= BASE_URL ?>page/logout.php" class="user" aria-label="Đăng xuất">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
    <path d="m2 12 5 4v-3h9v-2H7V8z"/>
    <path d="M13.001 2.999a8.938 8.938 0 0 0-6.364 2.637L8.051 7.05c1.322-1.322 3.08-2.051 4.95-2.051s3.628.729 4.95 2.051 2.051 3.08 2.051 4.95-.729 3.628-2.051 4.95-3.08 2.051-4.95 2.051-3.628-.729-4.95-2.051l-1.414 1.414c1.699 1.7 3.959 2.637 6.364 2.637s4.665-.937 6.364-2.637c1.7-1.699 2.637-3.959 2.637-6.364s-.937-4.665-2.637-6.364a8.938 8.938 0 0 0-6.364-2.637z"/>
  </svg>
  <span class="nav-label">Đăng xuất</span>
</a>

</nav>