<?php
/**
 * Class Product
 * Model quản lý bảng 'products'
 */
class Product {
    private mysqli $conn;
    private string $table = "products";

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng products
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một product
    public function create(string $product_name, string $description, int $category_id, int $account_id, string $thumbnail_url, float $price) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (product_name, description, category_id, account_id, thumbnail_url, price) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisd", $product_name, $description, $category_id, $account_id, $thumbnail_url, $price);
        return $stmt->execute();
    }

    // Cập nhật product theo ID
    public function update(int $product_id, string $product_name, string $description, int $category_id, int $account_id, string $thumbnail_url, float $price) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET product_name=?, description=?, category_id=?, account_id=?, thumbnail_url=?, price=? 
            WHERE product_id=?");
        $stmt->bind_param("ssiisdi", $product_name, $description, $category_id, $account_id, $thumbnail_url, $price, $product_id);
        return $stmt->execute();
    }

    // Xóa product theo ID
    public function delete(int $product_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE product_id=?");
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }
}
?>
