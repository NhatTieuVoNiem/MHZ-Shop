/**
 * ranking.js — Khởi tạo biểu đồ trang Bảng xếp hạng (ranking.php)
 * Phụ thuộc: Chart.js (CDN), canvas #ethPriceChart và #statsDonutChart
 * Dữ liệu do PHP gắn vào thuộc tính data-* trên từng canvas
 */
(function () {
  // Màu đồng bộ với giao diện tối trong ranking.css
  const purple = '#7B61FF';
  const purpleSoft = 'rgba(123, 97, 255, 0.35)';
  const gridColor = 'rgba(108, 122, 160, 0.25)';
  const tickColor = '#6C7AA0';
  const cardBg = '#1a1a2e';

  /** Biểu đồ đường: giá ETH trung bình theo ngày (cột giữa dashboard) */
  function initEthChart() {
    const canvas = document.getElementById('ethPriceChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const values = JSON.parse(canvas.dataset.values || '[]');
    const max = Number(canvas.dataset.max) || 350;
    const step = Number(canvas.dataset.step) || 50;

    new Chart(canvas, {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            data: values,
            borderColor: purple,
            // Vùng dưới đường: gradient tím mờ → trong suốt
            backgroundColor: (context) => {
              const { chart } = context;
              const { ctx, chartArea } = chart;
              if (!chartArea) return purpleSoft;
              const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
              gradient.addColorStop(0, 'rgba(123, 97, 255, 0.45)');
              gradient.addColorStop(1, 'rgba(26, 26, 46, 0)');
              return gradient;
            },
            fill: true,
            tension: 0.35,
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: purple,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 7,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#110e20',
            titleColor: '#fff',
            bodyColor: '#b7b7d1',
            borderColor: purple,
            borderWidth: 1,
            callbacks: {
              label: (ctx) => ` ${ctx.parsed.y} ETH`,
            },
          },
        },
        scales: {
          x: {
            grid: { color: gridColor, drawBorder: false },
            ticks: { color: tickColor, font: { size: 11 } },
          },
          y: {
            min: 0,
            max,
            ticks: {
              stepSize: step,
              color: tickColor,
              font: { size: 11 },
            },
            grid: { color: gridColor, drawBorder: false },
          },
        },
      },
    });
  }

  /** Biểu đồ donut: tỷ lệ đơn đã bán / đã hủy (cột phải dashboard) */
  function initStatsChart() {
    const canvas = document.getElementById('statsDonutChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const sold = Number(canvas.dataset.sold) || 0;
    const cancelled = Number(canvas.dataset.cancelled) || 0;

    new Chart(canvas, {
      type: 'doughnut',
      data: {
        labels: ['Đã bán', 'Đã hủy'],
        datasets: [
          {
            data: [sold, cancelled],
            backgroundColor: [purple, cardBg],
            borderColor: [purple, '#6C7AA0'],
            borderWidth: [0, 2],
            hoverOffset: 6,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '72%',
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#110e20',
            titleColor: '#fff',
            bodyColor: '#b7b7d1',
          },
        },
      },
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    initEthChart();
    initStatsChart();
  });
})();
