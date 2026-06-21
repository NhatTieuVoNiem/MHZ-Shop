<?php

/**
 * Class Order
 * Model quản lý bảng 'orders'
 */
class Order
{
    private mysqli $conn;
    private string $table = "orders";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng orders
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một order
    public function create(
        int $account_id,
        float $total_amount,
        string $status
    ) {
        $stmt = $this->conn->prepare("
        INSERT INTO {$this->table}
        (
            account_id,
            total_amount,
            status
        )
        VALUES (?, ?, ?)
    ");

        $stmt->bind_param(
            "ids",
            $account_id,
            $total_amount,
            $status
        );

        return $stmt->execute();
    }

    // Cập nhật order theo ID
    public function update(int $order_id, int $account_id, string $order_date, float $total_amount, string $status)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET account_id=?, order_date=?, total_amount=?, status=? WHERE order_id=?");
        $stmt->bind_param("isdsi", $account_id, $order_date, $total_amount, $status, $order_id);
        return $stmt->execute();
    }

    // Xóa order theo ID
    public function delete(int $order_id)
    {
        $this->conn->begin_transaction();

        try {

            $stmt = $this->conn->prepare("
        DELETE FROM order_items
        WHERE order_id = ?
    ");

            $stmt->bind_param("i", $order_id);
            $stmt->execute();

            $stmt = $this->conn->prepare("
        DELETE FROM orders
        WHERE order_id = ?
    ");

            $stmt->bind_param("i", $order_id);
            $stmt->execute();

            $this->conn->commit();

            return true;
        } catch (Exception $e) {

            $this->conn->rollback();

            return false;
        }
    }

    // Thống kê
    public function countAll()
    {
        $sql = "SELECT COUNT(*) total FROM orders";
        return $this->conn->query($sql)->fetch_assoc()['total'];
    }

    public function countByStatus($status)
    {
        $stmt = $this->conn->prepare("
        SELECT COUNT(*) total
        FROM orders
        WHERE status = ?
    ");

        $stmt->bind_param("s", $status);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    // Danh sách đơn hàng
    public function getOrdersWithDetails()
    {
        $sql = "
        SELECT
            o.order_id,
            o.total_amount,
            o.status,
            o.created_at,

            a.account_id,
            a.username,
            a.email,

            p.product_name

        FROM orders o

        LEFT JOIN accounts a
            ON o.account_id = a.account_id

        LEFT JOIN order_items oi
            ON o.order_id = oi.order_id

        LEFT JOIN products p
            ON oi.product_id = p.product_id

        ORDER BY o.created_at DESC
    ";

        return $this->conn->query($sql);
    }
    public function getById($orderId)
    {
        $sql = "
        SELECT
            o.*,
            a.username,
            a.email

        FROM orders o

        LEFT JOIN accounts a
            ON o.account_id = a.account_id

        WHERE o.order_id = ?

        LIMIT 1
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $orderId);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
    public function getOrderItems($orderId)
    {
        $sql = "
        SELECT

            oi.quantity,

            p.product_id,
            p.product_name,
            p.thumbnail_url,
            p.price

        FROM order_items oi

        JOIN products p
            ON oi.product_id = p.product_id

        WHERE oi.order_id = ?
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $orderId);

        $stmt->execute();

        return $stmt->get_result();
    }
    // Tổng doanh thu
    public function getTotalRevenue()
    {
        $sql = "
        SELECT COALESCE(SUM(total_amount),0) total
        FROM orders
        WHERE status = 'completed'
    ";

        return $this->conn->query($sql)->fetch_assoc()['total'];
    }
    // Doanh thu theo tháng
    public function getRevenueByMonth()
    {
        $sql = "
        SELECT
            MONTH(created_at) month_num,
            SUM(total_amount) revenue,
            COUNT(*) total_orders

        FROM orders

        WHERE YEAR(created_at)=YEAR(CURDATE())

        GROUP BY MONTH(created_at)

        ORDER BY month_num
    ";

        return $this->conn->query($sql);
    }
    // Thống kê trạng thái đơn hàng
    public function getStatusStatistics()
    {
        $sql = "
        SELECT
            status,
            COUNT(*) total
        FROM orders
        GROUP BY status
    ";

        return $this->conn->query($sql);
    }
    // Tỷ lệ hoàn thành
    public function getCompletionRate()
    {
        $sql = "
        SELECT
        ROUND(
            (
                SUM(
                    CASE
                    WHEN status='completed'
                    THEN 1
                    ELSE 0
                    END
                )
                /
                NULLIF(COUNT(*),0)
            ) * 100
        ,2) rate
        FROM orders
    ";

        $result = $this->conn->query($sql);

        return $result->fetch_assoc()['rate'] ?? 0;
    }
    public function createAndGetId(
        int $account_id,
        float $total_amount,
        string $status
    ) {
        $stmt = $this->conn->prepare("
        INSERT INTO {$this->table}
        (
            account_id,
            total_amount,
            status
        )
        VALUES (?, ?, ?)
    ");

        $stmt->bind_param(
            "ids",
            $account_id,
            $total_amount,
            $status
        );

        $stmt->execute();

        return $this->conn->insert_id;
    }
    public function getOrdersByAccount($accountId)
    {
        $sql = "
        SELECT *
        FROM orders
        WHERE account_id = ?
        ORDER BY created_at DESC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $accountId);
        $stmt->execute();

        return $stmt->get_result();
    }
    public function countItems($orderId)
    {
        $stmt = $this->conn->prepare("
        SELECT SUM(quantity) total
        FROM order_items
        WHERE order_id = ?
    ");

        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    }
}
