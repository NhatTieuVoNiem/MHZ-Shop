<?php
class AccountProfile
{
    private mysqli $conn;
    private string $table = "account_profiles";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng account_profiles
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một profile
    public function create(int $account_id, string $last_name, string $middle_name, string $first_name, ?int $gender_id, ?string $date_of_birth, ?string $bio, ?string $phone)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (account_id, last_name, middle_name, first_name, gender_id, date_of_birth, bio, phone) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssisss", $account_id, $last_name, $middle_name, $first_name, $gender_id, $date_of_birth, $bio, $phone);
        return $stmt->execute();
    }

    // Cập nhật profile theo ID
    public function update(int $profile_id, string $last_name, string $middle_name, string $first_name, ?int $gender_id, ?string $date_of_birth, ?string $bio, ?string $phone)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET last_name=?, middle_name=?, first_name=?, gender_id=?, date_of_birth=?, bio=?, phone=? 
            WHERE profile_id=?");
        $stmt->bind_param("sssisssi", $last_name, $middle_name, $first_name, $gender_id, $date_of_birth, $bio, $phone, $profile_id);
        return $stmt->execute();
    }

    // Xóa profile theo ID
    public function delete(int $profile_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE profile_id=?");
        $stmt->bind_param("i", $profile_id);
        return $stmt->execute();
    }
    // tìm theo mã ID
    public function findByAccountId($account_id)
    {
        $stmt = $this->conn->prepare("
        SELECT *
        FROM account_profiles
        WHERE account_id = ?
        LIMIT 1
    ");

        $stmt->bind_param("i", $account_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}
