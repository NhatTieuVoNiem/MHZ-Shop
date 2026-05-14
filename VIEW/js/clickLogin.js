document.addEventListener("DOMContentLoaded", () => {
  const userBtn = document.querySelector('.user-btn');
  const modal = document.querySelector('.auth-modal');
  const createBtn = document.querySelector('.create-btn');
  const buyBtn = document.querySelector('.buy-btn');

  // Định nghĩa hàm xử lý chung
  function handleAuthClick(e, url) {
    e.preventDefault();
    const isLogged = userBtn && userBtn.dataset.logged === "true";
    if (isLogged) {
      window.location.href = url;
    } else {
      modal.classList.add('active');
    }
  }

  if (userBtn) {
    userBtn.addEventListener('click', () => {
      const isLogged = userBtn.dataset.logged === "true";
      if (isLogged) {
        window.location.href = "<?= BASE_URL ?>page/user.php";
      } else {
        modal.classList.add('active');
      }
    });
  }

  if (createBtn) {
    createBtn.addEventListener('click', (e) => {
      handleAuthClick(e, createBtn.getAttribute('href'));
    });
  }

  if (buyBtn) {
    buyBtn.addEventListener('click', (e) => {
      handleAuthClick(e, buyBtn.getAttribute('href'));
    });
  }

  // click ngoài để đóng
  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.remove('active');
      }
    });
  }
});
