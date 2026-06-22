<?php

/**
 * Class ProductReview
 * Model quản lý bảng 'product_reviews'
 */
class ProductReview
{
    private mysqli $conn;
    private string $table = "product_reviews";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng product_reviews
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một review cho sản phẩm
    public function create(int $account_id, int $product_id, int $rating, string $comment)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (account_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $account_id, $product_id, $rating, $comment);
        return $stmt->execute();
    }

    // Cập nhật review theo ID
    public function update(int $review_id, int $account_id, int $product_id, int $rating, string $comment)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET account_id=?, product_id=?, rating=?, comment=? WHERE review_id=?");
        $stmt->bind_param("iiisi", $account_id, $product_id, $rating, $comment, $review_id);
        return $stmt->execute();
    }

    // Xóa review theo ID
    public function delete(int $review_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE review_id=?");
        $stmt->bind_param("i", $review_id);
        return $stmt->execute();
    }
}
