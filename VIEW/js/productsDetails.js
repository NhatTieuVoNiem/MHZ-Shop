/**
 * productsDetails.js — "Xem thêm" phần đánh giá sản phẩm
 */
document.addEventListener('DOMContentLoaded', () => {
  const loadMoreBtn = document.getElementById('review-load-more');
  const reviewList = document.getElementById('review-list');

  if (!loadMoreBtn || !reviewList) {
    return;
  }

  loadMoreBtn.addEventListener('click', () => {
    reviewList.classList.add('is-expanded');
    loadMoreBtn.classList.add('is-hidden');
    loadMoreBtn.setAttribute('aria-expanded', 'true');
  });
});
