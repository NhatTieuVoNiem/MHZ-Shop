<?php

/**
 * Model quản lý bảng `product_views` — lưu lịch sử lượt xem sản phẩm
 *
 * Mỗi lần user bấm "Xem chi tiết" / "Xem trước", controller gọi create()
 * để thêm dòng mới (trừ khi cùng account + product đã xem trong 5 phút).
 */
class ProductView
{
    private mysqli $conn;
    private string $table = "product_views";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    /** Lấy toàn bộ lượt xem (dùng đếm thủ công trên productsDetails.php) */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    /**
     * Thêm một lượt xem mới
     *
     * @param int $account_id ID tài khoản (guest thường dùng 1 từ controller)
     * @param int $product_id  ID sản phẩm được xem
     * @return bool true nếu INSERT thành công; false nếu trùng trong 5 phút
     */
   public function create(?int $account_id, int $product_id)
{
    // Chống spam 5 phút — chỉ áp dụng cho user đã đăng nhập
    if ($account_id !== null) {
        $check = $this->conn->prepare("
            SELECT view_id FROM product_views 
            WHERE account_id = ? AND product_id = ?
            AND viewed_at >= NOW() - INTERVAL 5 MINUTE
            LIMIT 1
        ");
        $check->bind_param("ii", $account_id, $product_id);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) return false;
    }

    $stmt = $this->conn->prepare("
        INSERT INTO product_views (account_id, product_id, viewed_at) 
        VALUES (?, ?, NOW())
    ");
    $stmt->bind_param("ii", $account_id, $product_id);
    return $stmt->execute();
}
}
