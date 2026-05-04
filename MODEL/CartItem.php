<?php
class CartItem {
    private mysqli $conn;
    private string $table = "cart_items";

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng cart_items
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một cart_item
    public function create(int $cart_id, int $product_id, int $quantity) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
        return $stmt->execute();
    }

    // Cập nhật cart_item theo ID
    public function update(int $cart_item_id, int $cart_id, int $product_id, int $quantity) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET cart_id=?, product_id=?, quantity=? WHERE cart_item_id=?");
        $stmt->bind_param("iiii", $cart_id, $product_id, $quantity, $cart_item_id);
        return $stmt->execute();
    }

    // Xóa cart_item theo ID
    public function delete(int $cart_item_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE cart_item_id=?");
        $stmt->bind_param("i", $cart_item_id);
        return $stmt->execute();
    }
}
?>
