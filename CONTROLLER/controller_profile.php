<?php

require_once __DIR__ . '/../MODEL/connect.php';
require_once __DIR__ . '/../MODEL/Account.php';
require_once __DIR__ . '/../MODEL/Product.php';

class ControllerProfile
{
    private Account $accountModel;
    private Product $productModel;

    // Khởi tạo controller
    public function __construct(mysqli $conn)
    {
        $this->accountModel = new Account($conn);
        $this->productModel = new Product($conn);
    }

    // Lấy toàn bộ dữ liệu hiển thị trên trang hồ sơ
    public function getProfileData(int $account_id): array
    {
        return [

            // Thông tin tài khoản và hồ sơ người dùng
            'user' => $this->accountModel->getFullInfo($account_id),

            // Danh sách sản phẩm của người dùng
            'products' => $this->productModel->getByAccountId($account_id),

            // Tổng số sản phẩm đã đăng
            'totalProducts' => $this->productModel->countByAccountId($account_id)

        ];
    }
}
