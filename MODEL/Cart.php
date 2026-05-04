<?php
class Cart {
    private mysqli $conn;
    private string $table = "carts";

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng carts
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một cart
    public function create(int $account_id) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (account_id) VALUES (?)");
        $stmt->bind_param("i", $account_id);
        return $stmt->execute();
    }

    // Cập nhật cart theo ID
    public function update(int $cart_id, int $account_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET account_id=? WHERE cart_id=?");
        $stmt->bind_param("ii", $account_id, $cart_id);
        return $stmt->execute();
    }

    // Xóa cart theo ID
    public function delete(int $cart_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE cart_id=?");
        $stmt->bind_param("i", $cart_id);
        return $stmt->execute();
    }
}
?>
