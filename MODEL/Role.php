<?php
class Role {
    private $conn;
    private $table = "roles";

    public function __construct($db) {
        $this->conn = $db;
    }
    // Lấy tất cả bản ghi trong bảng roles
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }
    // Tạo mới một role
    public function create($role_name) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (role_name) VALUES (?)");
        $stmt->bind_param("s", $role_name);
        return $stmt->execute();
    }
}
?>
