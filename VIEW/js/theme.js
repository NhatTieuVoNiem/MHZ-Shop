// theme.js

document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll('.regime button');
  const lightBtn = document.querySelector('[aria-label="Chế độ sáng"]');
  const darkBtn = document.querySelector('[aria-label="Chế độ tối"]');

  // ===== Load trạng thái đã lưu =====
  const savedTheme = localStorage.getItem("theme");

  if (savedTheme === "dark") {
    document.body.classList.add("dark");
    darkBtn.classList.add("active");
  } else {
    lightBtn.classList.add("active");
  }

  // ===== Xử lý click =====
  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      buttons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });

  // ===== Light mode =====
  lightBtn.addEventListener("click", () => {
    document.body.classList.remove("dark");
    localStorage.setItem("theme", "light");
  });

  // ===== Dark mode =====
  darkBtn.addEventListener("click", () => {
    document.body.classList.add("dark");
    localStorage.setItem("theme", "dark");
  });
});