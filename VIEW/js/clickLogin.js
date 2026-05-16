document.addEventListener("DOMContentLoaded", () => {

  const userBtn = document.querySelector('.user-btn');
  const modal = document.querySelector('.auth-modal');

  const createBtn = document.querySelector('.create-btn');
  const buyBtn = document.querySelector('.buy-btn');
  const likeBtn = document.querySelector('.btn-like');
   const loginPopups = document.querySelectorAll('.login-popup');

  function handleAuthClick(e, url) {

    e.preventDefault();

    const isLogged =
      userBtn &&
      userBtn.dataset.logged === "true";

    if (isLogged) {

      window.location.href = url;

    } else if (modal) {

      modal.classList.add('active');

    }
  }

  // USER
  if (userBtn) {

    userBtn.addEventListener('click', () => {

      const isLogged =
        userBtn.dataset.logged === "true";

      if (isLogged) {

        window.location.href =
          BASE_URL + "page/user.php";

      } else if (modal) {

        modal.classList.add('active');

      }

    });

  }

  // CREATE
  if (createBtn) {

    createBtn.addEventListener('click', (e) => {

      handleAuthClick(
        e,
        createBtn.getAttribute('href')
      );

    });

  }

  // BUY
  if (buyBtn) {

    buyBtn.addEventListener('click', (e) => {

      handleAuthClick(
        e,
        buyBtn.getAttribute('href')
      );

    });

  }

  // LIKE
  if (likeBtn) {

    likeBtn.addEventListener('click', (e) => {

      handleAuthClick(
        e,
        likeBtn.getAttribute('href')
      );

    });

  }
// LOGIN POPUP
if (loginPopups.length > 0) {
  loginPopups.forEach(btn => {
    btn.addEventListener('click', (e) => {
      handleAuthClick(e, btn.getAttribute('href'));
    });
  });
}
  // CLOSE MODAL
  if (modal) {

    modal.addEventListener('click', (e) => {

      if (e.target === modal) {

        modal.classList.remove('active');

      }

    });

  }

});