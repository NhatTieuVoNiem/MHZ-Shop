document.addEventListener("DOMContentLoaded", () => {
  const notifyBtn = document.querySelector('.notify');
  const wrapper = document.querySelector('.notify-wrapper');
  const badge = document.querySelector('.notify .badge');

  // ❌ Nếu thiếu element thì dừng (tránh crash)
  if (!notifyBtn || !wrapper || !badge) return;

  // ===== SỐ THÔNG BÁO =====
  const count = 0; // sau này thay bằng dữ liệu thật

  if (count > 0) {
    badge.textContent = count > 99 ? "99+" : count;
    badge.style.display = "flex";
  } else {
    badge.textContent = "";
    badge.style.display = "none";
  }

  // ===== TOGGLE DROPDOWN =====
  notifyBtn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation(); // 👉 tránh bị click document đóng ngay

    wrapper.classList.toggle('active');
  });

  // ===== CLICK NGOÀI ĐỂ ĐÓNG =====
  document.addEventListener('click', (e) => {
    if (!wrapper.contains(e.target)) {
      wrapper.classList.remove('active');
    }
  });
});