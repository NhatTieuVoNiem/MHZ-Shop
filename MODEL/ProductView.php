<?php
/**
 * Class ProductView
 * Model quản lý bảng 'product_views'
 */
class ProductView {
    private mysqli $conn;
    private string $table = "product_views";

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng product_views
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một view cho sản phẩm
    public function create(int $account_id, int $product_id, string $view_date) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (account_id, product_id, view_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $account_id, $product_id, $view_date);
        return $stmt->execute();
    }

    // Cập nhật view theo ID
    public function update(int $view_id, int $account_id, int $product_id, string $view_date) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET account_id=?, product_id=?, view_date=? WHERE view_id=?");
        $stmt->bind_param("iisi", $account_id, $product_id, $view_date, $view_id);
        return $stmt->execute();
    }

    // Xóa view theo ID
    public function delete(int $view_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE view_id=?");
        $stmt->bind_param("i", $view_id);
        return $stmt->execute();
    }
}
?>
