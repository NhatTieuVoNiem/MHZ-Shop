// Nút "Xem trước" — dùng closest() thay vì getElementById để tránh duplicate ID
document.querySelectorAll('.btn-preview').forEach(btn => {
    btn.addEventListener('click', function () {
        const productId  = this.dataset.productId;
        const previewUrl = this.dataset.previewUrl;

        // Tìm form gần nhất trong cùng card, không dùng getElementById
        const card = this.closest('article') || document;
        const form = card.querySelector('#preview-form') 
                     || document.querySelector('#preview-form'); // fallback

        if (!form) return; // ← tránh crash khi không có preview-form
        form.querySelector('[name="product_id"]').value  = productId;
        form.querySelector('[name="redirect_url"]').value = previewUrl;
        form.submit();
    });
});

// Nút "Xem chi tiết"
document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function () {
        const productId = this.dataset.productId;
        const detailUrl = this.dataset.detailUrl;

        // Tìm form trong cùng article card → tránh duplicate ID
        const form = this.closest('article').querySelector('form');
        form.querySelector('[name="product_id"]').value   = productId;
        form.querySelector('[name="redirect_url"]').value = detailUrl;
        form.submit();
    });
});