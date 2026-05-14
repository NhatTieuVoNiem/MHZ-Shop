<?php
class Account  {
    private mysqli $conn;
    private string $table = "accounts";

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng accounts
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một account
    public function create(string $username, string $email, string $password_hash, int $role_id) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (username, email, password_hash, role_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $email, $password_hash, $role_id);
        return $stmt->execute();
    }

    // Cập nhật account theo ID
    public function update(int $account_id, string $username, string $email, string $password_hash, int $role_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET username=?, email=?, password_hash=?, role_id=? WHERE account_id=?");
        $stmt->bind_param("sssii", $username, $email, $password_hash, $role_id, $account_id);
        return $stmt->execute();
    }

    // Xóa account theo ID
    public function delete(int $account_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE account_id=?");
        $stmt->bind_param("i", $account_id);
        return $stmt->execute();
    }

        // Tìm account theo email
    public function findByEmail(string $email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // Tìm account theo username
    public function findByUsername(string $username) {

        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
?>
