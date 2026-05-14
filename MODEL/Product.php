<?php

/**
 * Class Product
 * Model quản lý bảng 'products'
 */
class Product
{
    private mysqli $conn;
    private string $table = "products";

    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả bản ghi trong bảng products
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql);
    }

    // Tạo mới một product
    public function create(string $product_name, string $description, int $category_id, int $account_id, string $thumbnail_url, float $price)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (product_name, description, category_id, account_id, thumbnail_url, price) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisd", $product_name, $description, $category_id, $account_id, $thumbnail_url, $price);
        return $stmt->execute();
    }

    // Cập nhật product theo ID
    public function update(int $product_id, string $product_name, string $description, int $category_id, int $account_id, string $thumbnail_url, float $price)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET product_name=?, description=?, category_id=?, account_id=?, thumbnail_url=?, price=? 
            WHERE product_id=?");
        $stmt->bind_param("ssiisdi", $product_name, $description, $category_id, $account_id, $thumbnail_url, $price, $product_id);
        return $stmt->execute();
    }

    // Xóa product theo ID
    public function delete(int $product_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE product_id=?");
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }
    public function getFeaturedNFT()
    {
        $sql = "
    SELECT
        p.product_id,
        p.product_name,
        p.price,
        p.created_at,
        p.thumbnail_url,
        p.description,
        p.preview_url, 
        a.username,
        a.avatar_url
    FROM products p
    LEFT JOIN accounts a
        ON p.account_id = a.account_id
    ORDER BY RAND()
    LIMIT 1
    ";

        $result = $this->conn->query($sql);

        // DỮ LIỆU MẶC ĐỊNH
        $data = [
            "product_id"   => 0,
            "product_name" => "Birghten LQ",
            "price"        => "0.15",
            "created_at"   =>date("Y-m-d"),
            "thumbnail"    => BASE_URL . "assets/images/home/topSection/NFT.png",
            "description"  => "Digital marketplace for crypto collectibles and non fungible tokens",
            "username"     => "John Abraham",
            "avatar_url"   => BASE_URL . "assets/images/home/topSection/Avatar.png",
            "preview_url"  => BASE_URL . "page/bid.php?id=0"
        ];

        // =========================
        // NẾU CÓ DỮ LIỆU
        // =========================
        if ($result && $row = $result->fetch_assoc()) {

            $data["product_id"] = $row["product_id"] ?? 0;

            if (!empty($row["product_name"])) {
                $data["product_name"] = $row["product_name"];
            }

            if (!empty($row["price"])) {
                $data["price"] = $row["price"];
            }
            if (!empty($row["created_at"])) {
                $data["created_at"] = $row["created_at"];
            }
            if (!empty($row["thumbnail_url"])) {
                $data["thumbnail"] = BASE_URL . $row["thumbnail_url"];
            }

            if (!empty($row["description"])) {
                $data["description"] = $row["description"];
            }

            if (!empty($row["username"])) {
                $data["username"] = $row["username"];
            }

            if (!empty($row["avatar_url"])) {
                $data["avatar_url"] = BASE_URL . $row["avatar_url"];
            }
            if (!empty($row["preview_url"])) {
                $data["preview_url"] = $row["preview_url"];
            }
        }

        return $data;
    }
}
