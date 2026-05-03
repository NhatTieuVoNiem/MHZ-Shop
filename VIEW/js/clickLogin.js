document.addEventListener("DOMContentLoaded", () => {
  const userBtn = document.querySelector('.user-btn');
  const modal = document.querySelector('.auth-modal');

  if (!userBtn) return;

  userBtn.addEventListener('click', () => {
    const isLogged = userBtn.dataset.logged === "true";

    if (isLogged) {
      // đã login → vào profile
      window.location.href = "<?= BASE_URL ?>page/user.php";
    } else {
      // chưa login → mở popup
      modal.classList.add('active');
    }
  });

  // click ngoài để đóng
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('active');
    }
  });
});