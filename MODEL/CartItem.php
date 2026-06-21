<?php
class CartItem
{
    private mysqli $conn;
    private string $table = "cart_items";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng cart_items
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một cart_item
    public function create(int $cart_id, int $product_id, int $quantity)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
        return $stmt->execute();
    }

    // Cập nhật cart_item theo ID
    public function update(int $cart_item_id, int $cart_id, int $product_id, int $quantity)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET cart_id=?, product_id=?, quantity=? WHERE cart_item_id=?");
        $stmt->bind_param("iiii", $cart_id, $product_id, $quantity, $cart_item_id);
        return $stmt->execute();
    }

    // Xóa cart_item theo ID
    public function delete(int $cart_item_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE cart_item_id=?");
        $stmt->bind_param("i", $cart_item_id);
        return $stmt->execute();
    }
    public function getByCartAndProduct(int $cart_id, int $product_id)
    {
        $stmt = $this->conn->prepare("
        SELECT * FROM {$this->table}
        WHERE cart_id=? AND product_id=?
    ");

        $stmt->bind_param("ii", $cart_id, $product_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function increaseQuantity(int $cart_item_id)
    {
        $stmt = $this->conn->prepare("
        UPDATE {$this->table}
        SET quantity = quantity + 1
        WHERE cart_item_id=?
    ");

        $stmt->bind_param("i", $cart_item_id);

        return $stmt->execute();
    }

    public function getByCartId(int $cart_id)
    {
        $stmt = $this->conn->prepare("
        SELECT ci.*, p.product_name, p.price, p.thumbnail_url
        FROM cart_items ci
        INNER JOIN products p ON ci.product_id = p.product_id
        WHERE ci.cart_id=?
    ");

        $stmt->bind_param("i", $cart_id);
        $stmt->execute();

        return $stmt->get_result();
    }
    public function decreaseQuantity(int $cart_item_id)
    {
        $stmt = $this->conn->prepare("
        UPDATE {$this->table}
        SET quantity = quantity - 1
        WHERE cart_item_id=? AND quantity > 1
    ");

        $stmt->bind_param("i", $cart_item_id);

        return $stmt->execute();
    }
    public function getById(int $cart_item_id)
{
    $stmt = $this->conn->prepare("
        SELECT * FROM {$this->table}
        WHERE cart_item_id=?
    ");

    $stmt->bind_param("i", $cart_item_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}
}
