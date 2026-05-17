document.querySelectorAll('.btn-preview').forEach(btn => {
    btn.addEventListener('click', function () {
        const productId  = this.dataset.productId;
        const previewUrl = this.dataset.previewUrl;

        console.log('Gán product_id:', productId);
        console.log('Gán preview_url:', previewUrl);

        document.getElementById('form-product-id').value   = productId;
        document.getElementById('form-redirect-url').value = previewUrl;

        console.log('Giá trị trong form:', document.getElementById('form-redirect-url').value);

        document.getElementById('preview-form').submit();
    });
});
document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function () {

        const productId = this.dataset.productId;
        const detailUrl = this.dataset.detailUrl;

        console.log('Product ID:', productId);
        console.log('Detail URL:', detailUrl);

        document.getElementById('detail-product-id').value = productId;
        document.getElementById('detail-redirect-url').value = detailUrl;

        document.getElementById('detail-form').submit();
    });
});