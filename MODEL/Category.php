<?php

/**
 * Class Category
 * Model quản lý bảng 'categories'
 */
class Category
{
    private mysqli $conn;
    private string $table = "categories";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng categories
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một category
    public function create(string $name, string $description)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (category_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    // Cập nhật category theo ID
    public function update(int $category_id, string $name, string $description)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET category_name=?, description=? WHERE category_id=?");
        $stmt->bind_param("ssi", $name, $description, $category_id);
        return $stmt->execute();
    }

    // Xóa category theo ID
    public function delete(int $category_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE category_id=?");
        $stmt->bind_param("i", $category_id);
        return $stmt->execute();
    }
}
