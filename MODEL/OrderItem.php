<?php

/**
 * Class OrderItem
 * Model quản lý bảng 'order_items'
 */
class OrderItem
{
    private mysqli $conn;
    private string $table = "order_items";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng order_items
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một order_item
    public function create(
        int $order_id,
        int $product_id,
        int $quantity
    ) {
        $stmt = $this->conn->prepare("
        INSERT INTO {$this->table}
        (
            order_id,
            product_id,
            quantity
        )
        VALUES (?, ?, ?)
    ");

        $stmt->bind_param(
            "iii",
            $order_id,
            $product_id,
            $quantity
        );

        return $stmt->execute();
    }

    // Cập nhật order_item theo ID
    public function update(int $order_item_id, int $order_id, int $product_id, int $quantity, float $price)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET order_id=?, product_id=?, quantity=?, price=? WHERE order_item_id=?");
        $stmt->bind_param("iiidi", $order_id, $product_id, $quantity, $price, $order_item_id);
        return $stmt->execute();
    }

    // Xóa order_item theo ID
    public function delete(int $order_item_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE order_item_id=?");
        $stmt->bind_param("i", $order_item_id);
        return $stmt->execute();
    }
}
