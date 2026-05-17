<?php

/**
 * Class ProductView
 * Model quản lý bảng 'product_views'
 */
class ProductView
{
    private mysqli $conn;
    private string $table = "product_views";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng product_views
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một view cho sản phẩm
    public function create(int $account_id, int $product_id)
    {
        // Kiểm tra đã xem trong 5 phút chưa
        $check = $this->conn->prepare("
        SELECT view_id FROM product_views 
        WHERE account_id = ? AND product_id = ?
        AND viewed_at >= NOW() - INTERVAL 5 MINUTE
        LIMIT 1
    ");
        $check->bind_param("ii", $account_id, $product_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            return false;
        }

        // Thêm mới
        $stmt = $this->conn->prepare("
        INSERT INTO product_views (account_id, product_id, viewed_at) 
        VALUES (?, ?, NOW())
    ");

        $stmt->bind_param("ii", $account_id, $product_id);
        return $stmt->execute();
    }
}
