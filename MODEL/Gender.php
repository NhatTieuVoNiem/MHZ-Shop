<?php

/**
 * Class Gender
 * Model quản lý bảng 'genders'
 */
class Gender
{
    private mysqli $conn;
    private string $table = "genders";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng genders
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một gender
    public function create(string $gender_name)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (gender_name) VALUES (?)");
        $stmt->bind_param("s", $gender_name);
        return $stmt->execute();
    }

    // Cập nhật gender theo ID
    public function update(int $gender_id, string $gender_name)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET gender_name=? WHERE gender_id=?");
        $stmt->bind_param("si", $gender_name, $gender_id);
        return $stmt->execute();
    }

    // Xóa gender theo ID
    public function delete(int $gender_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE gender_id=?");
        $stmt->bind_param("i", $gender_id);
        return $stmt->execute();
    }
}
