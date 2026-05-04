<?php
/**
 * Class Order
 * Model quản lý bảng 'orders'
 */
class Order {
    private mysqli $conn;
    private string $table = "orders";

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng orders
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một order
    public function create(int $account_id, string $order_date, float $total_amount, string $status) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (account_id, order_date, total_amount, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isds", $account_id, $order_date, $total_amount, $status);
        return $stmt->execute();
    }

    // Cập nhật order theo ID
    public function update(int $order_id, int $account_id, string $order_date, float $total_amount, string $status) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET account_id=?, order_date=?, total_amount=?, status=? WHERE order_id=?");
        $stmt->bind_param("isdsi", $account_id, $order_date, $total_amount, $status, $order_id);
        return $stmt->execute();
    }

    // Xóa order theo ID
    public function delete(int $order_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE order_id=?");
        $stmt->bind_param("i", $order_id);
        return $stmt->execute();
    }
}
?>
