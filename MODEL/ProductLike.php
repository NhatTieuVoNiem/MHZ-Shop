<?php
/**
 * Class ProductLike
 * Model quản lý bảng 'product_likes'
 */
class ProductLike {
    private mysqli $conn;
    private string $table = "product_likes";

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng product_likes
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một like cho sản phẩm
    public function create(int $account_id, int $product_id) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (account_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $account_id, $product_id);
        return $stmt->execute();
    }

    // Cập nhật like theo ID (ví dụ đổi account hoặc product liên kết)
    public function update(int $like_id, int $account_id, int $product_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET account_id=?, product_id=? WHERE like_id=?");
        $stmt->bind_param("iii", $account_id, $product_id, $like_id);
        return $stmt->execute();
    }

    // Xóa like theo ID
    public function delete(int $like_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE like_id=?");
        $stmt->bind_param("i", $like_id);
        return $stmt->execute();
    }
}
?>
