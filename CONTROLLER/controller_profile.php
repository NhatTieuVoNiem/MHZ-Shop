<?php

require_once __DIR__ . '/../MODEL/connect.php';
require_once __DIR__ . '/../MODEL/Account.php';
require_once __DIR__ . '/../MODEL/Product.php';

class ControllerProfile
{
    private Account $accountModel;
    private Product $productModel;

    public function __construct(mysqli $conn)
    {
        $this->accountModel = new Account($conn);
        $this->productModel = new Product($conn);
    }

    /**
     * Lấy toàn bộ dữ liệu trang hồ sơ
     */
    public function getProfileData(int $account_id): array
    {
        return [
            'user'          => $this->accountModel->getFullInfo($account_id),
            'products'      => $this->productModel->getByAccountId($account_id),
            'totalProducts' => $this->productModel->countByAccountId($account_id)
        ];
    }
}
