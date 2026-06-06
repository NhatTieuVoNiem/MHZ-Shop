<?php

class Account
{
    private mysqli $conn;
    private string $table = "accounts";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả tài khoản chưa bị vô hiệu hóa
    public function getAll()
    {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE is_deleted = 0
            ORDER BY account_id DESC
        ";

        return $this->conn->query($sql);
    }

    // Thêm tài khoản
    public function create(
        string $username,
        string $email,
        string $password_hash,
        int $role_id
    ) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table}
            (
                username,
                email,
                password_hash,
                role_id
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssi",
            $username,
            $email,
            $password_hash,
            $role_id
        );

        return $stmt->execute();
    }

    // Cập nhật tài khoản
    public function update(
        int $account_id,
        string $username,
        string $email,
        string $password_hash,
        int $role_id
    ) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET
                username = ?,
                email = ?,
                password_hash = ?,
                role_id = ?,
                updated_at = NOW()
            WHERE account_id = ?
        ");

        $stmt->bind_param(
            "sssii",
            $username,
            $email,
            $password_hash,
            $role_id,
            $account_id
        );

        return $stmt->execute();
    }

    // Vô hiệu hóa tài khoản (xóa mềm)
    public function delete(int $account_id)
    {
        $stmt = $this->conn->prepare("
            UPDATE accounts
            SET is_deleted = 1
            WHERE account_id = ?
            AND role_id <> 1
        ");

        $stmt->bind_param("i", $account_id);

        return $stmt->execute();
    }

    // Khôi phục tài khoản
    public function restore(int $account_id)
    {
        $stmt = $this->conn->prepare("
            UPDATE accounts
            SET is_deleted = 0
            WHERE account_id = ?
        ");

        $stmt->bind_param("i", $account_id);

        return $stmt->execute();
    }

    // Tìm theo ID
    public function findById(int $account_id)
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM accounts
            WHERE account_id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $account_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Tìm theo email
    public function findByEmail(string $email)
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM accounts
            WHERE email = ?
            AND is_deleted = 0
            LIMIT 1
        ");

        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Tìm theo username
    public function findByUsername(string $username)
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM accounts
            WHERE username = ?
            AND is_deleted = 0
            LIMIT 1
        ");

        $stmt->bind_param("s", $username);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Top người đăng nhiều sản phẩm nhất
    public function getTopCreators($limit = 8)
    {
        $sql = "
            SELECT
                a.account_id,
                a.username,
                a.avatar_url,
                COUNT(p.product_id) AS total_items
            FROM accounts a
            LEFT JOIN products p
                ON a.account_id = p.account_id
            WHERE a.is_deleted = 0
            GROUP BY a.account_id
            ORDER BY total_items DESC
            LIMIT ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }

    // Người bán doanh thu cao nhất
    public function getTopSeller()
    {
        $sql = "
            SELECT
                a.account_id,
                a.username,
                a.avatar_url,
                COALESCE(SUM(p.price * oi.quantity),0) AS revenue
            FROM accounts a
            JOIN products p
                ON a.account_id = p.account_id
            JOIN order_items oi
                ON p.product_id = oi.product_id
            WHERE a.is_deleted = 0
            GROUP BY a.account_id
            ORDER BY revenue DESC
            LIMIT 1
        ";

        return $this->conn->query($sql)->fetch_assoc();
    }

    // Người mua nhiều nhất
    public function getTopBuyer()
    {
        $sql = "
            SELECT
                a.account_id,
                a.username,
                a.avatar_url,
                COUNT(DISTINCT o.order_id) AS total_orders,
                COALESCE(SUM(oi.quantity),0) AS total_products
            FROM accounts a
            JOIN orders o
                ON a.account_id = o.account_id
            JOIN order_items oi
                ON o.order_id = oi.order_id
            WHERE a.is_deleted = 0
            GROUP BY a.account_id
            ORDER BY total_products DESC
            LIMIT 1
        ";

        return $this->conn->query($sql)->fetch_assoc();
    }

    // Top Seller
    public function getTopSellers($limit = 10)
    {
        $sql = "
            SELECT
                a.account_id,
                a.username,
                a.avatar_url,
                COUNT(DISTINCT p.product_id) AS total_products,
                COALESCE(SUM(p.price * oi.quantity),0) AS revenue
            FROM accounts a
            INNER JOIN products p
                ON a.account_id = p.account_id
            LEFT JOIN order_items oi
                ON p.product_id = oi.product_id
            WHERE a.is_deleted = 0
            GROUP BY a.account_id
            ORDER BY revenue DESC
            LIMIT ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }

    // Đếm người bán
    public function countSellers()
    {
        $sql = "
            SELECT COUNT(DISTINCT p.account_id) AS total
            FROM products p
            JOIN accounts a
                ON p.account_id = a.account_id
            WHERE a.is_deleted = 0
        ";

        return $this->conn->query($sql)->fetch_assoc()['total'];
    }

    // Top Buyer
    public function getTopBuyers($limit = 10)
    {
        $sql = "
            SELECT
                a.account_id,
                a.username,
                a.avatar_url,
                COUNT(DISTINCT o.order_id) AS total_orders,
                COALESCE(SUM(oi.quantity),0) AS total_products,
                COALESCE(SUM(o.total_amount),0) AS total_spent
            FROM accounts a
            INNER JOIN orders o
                ON a.account_id = o.account_id
            INNER JOIN order_items oi
                ON o.order_id = oi.order_id
            WHERE a.is_deleted = 0
            GROUP BY a.account_id
            ORDER BY total_products DESC
            LIMIT ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }

    // Đếm người mua
    public function countBuyers()
    {
        $sql = "
            SELECT COUNT(DISTINCT o.account_id) AS total
            FROM orders o
            JOIN accounts a
                ON o.account_id = a.account_id
            WHERE a.is_deleted = 0
        ";

        return $this->conn->query($sql)->fetch_assoc()['total'];
    }
    // Danh sách tài khoản bị vô hiệu hóa
public function getDeletedAccounts()
{
    $sql = "
        SELECT
            account_id,
            username,
            email,
            avatar_url,
            created_at
        FROM accounts
        WHERE is_deleted = 1
        ORDER BY account_id DESC
    ";

    return $this->conn->query($sql);
}
public function countDeletedAccounts()
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM accounts
        WHERE is_deleted = 1
    ";

    return $this->conn->query($sql)->fetch_assoc()['total'];
}
}