/**
 * productsView.js — Gửi form POST ghi lượt xem trước khi chuyển trang
 *
 * Cần có trên HTML:
 * - #preview-form + .btn-preview (nút "Xem trước" → redirect preview_url)
 * - #detail-form + .btn-detail (nút "Xem chi tiết" → redirect trang chi tiết)
 *
 * Form POST tới CONTROLLER/controller_products_view.php với action=trackView
 */

// Nút "Xem trước": ghi lượt xem rồi mở link preview của sản phẩm
document.querySelectorAll('.btn-preview').forEach(btn => {
    btn.addEventListener('click', function () {
        const productId  = this.dataset.productId;
        const previewUrl = this.dataset.previewUrl;

        document.getElementById('form-product-id').value   = productId;
        document.getElementById('form-redirect-url').value = previewUrl;

        document.getElementById('preview-form').submit();
    });
});

// Nút "Xem chi tiết": ghi lượt xem rồi chuyển tới productsDetails.php
document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function () {
        const productId = this.dataset.productId;
        const detailUrl = this.dataset.detailUrl;

        document.getElementById('detail-product-id').value = productId;
        document.getElementById('detail-redirect-url').value = detailUrl;

        document.getElementById('detail-form').submit();
    });
});
