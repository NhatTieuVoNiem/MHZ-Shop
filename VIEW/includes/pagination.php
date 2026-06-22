<?php

/** @var int $currentPage */
/** @var int $totalPages */

// Lấy params hiện tại, bỏ 'page' để tránh trùng
$params = $_GET;
unset($params['page']);
$filterQuery = http_build_query($params);
$base = '?' . ($filterQuery ? $filterQuery . '&' : '');
?>
<nav class="pagination" aria-label="Phân trang">

  <!-- Nút Trước -->
  <?php if ($currentPage > 1): ?>
    <a href="<?= $base ?>page=<?= $currentPage - 1 ?>">&laquo;</a>
  <?php else: ?>
    <span class="disabled">&laquo;</span>
  <?php endif; ?>

  <!-- Số trang -->
  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?php if ($i === $currentPage): ?>
      <span class="active"><?= $i ?></span>
    <?php else: ?>
      <a href="<?= $base ?>page=<?= $i ?>"><?= $i ?></a>
    <?php endif; ?>
  <?php endfor; ?>

  <!-- Nút Sau -->
  <?php if ($currentPage < $totalPages): ?>
    <a href="<?= $base ?>page=<?= $currentPage + 1 ?>">&raquo;</a>
  <?php else: ?>
    <span class="disabled">&raquo;</span>
  <?php endif; ?>

</nav>