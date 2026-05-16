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
    // lấy sản phẩm top
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
            "created_at"   => date("Y-m-d"),
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
    // Lấy 8 sản phẩm có lượt xem cao nhất
    public function getTrendingProducts($limit = 8)
    {
        $stmt = $this->conn->prepare("
        SELECT 
            p.*,
            a.username,
            a.avatar_url,
            COUNT(v.view_id) AS total_views

        FROM products p

        LEFT JOIN accounts a
            ON p.account_id = a.account_id

        LEFT JOIN product_views  v
            ON p.product_id = v.product_id

        GROUP BY p.product_id

        ORDER BY total_views DESC

        LIMIT ?
    ");

        $stmt->bind_param("i", $limit);

        $stmt->execute();

        return $stmt->get_result();
    }
    // Lấy sản phẩm theo ID
    public function getById($id)
    {

        $stmt = $this->conn->prepare("
        SELECT *
        FROM products
        WHERE product_id = ?
    ");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
    // Hàm lấy 4 sản phẩm có lượt xem nhiều nhất theo danh mục
    public function getTopViewedProductsByCategory($categoryId, $limit = 4)
    {
        $sql = "
            SELECT p.product_id, p.product_name, p.thumbnail_url, p.price, p.created_at,
                   COUNT(v.view_id) AS views
            FROM products p
            LEFT JOIN product_views v ON p.product_id = v.product_id
            WHERE p.category_id = ?
            GROUP BY p.product_id, p.product_name, p.thumbnail_url, p.price, p.created_at
            ORDER BY views DESC
            LIMIT ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $categoryId, $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
    // Hàm lấy danh sách sản phẩm theo điều kiện lọc
  public function filterProducts($keyword, $category, $price, $sort)
{
    $sql = "SELECT p.*,
                COUNT(DISTINCT pv.view_id)  AS view_count,
                COUNT(DISTINCT pl.like_id)  AS like_count
            FROM products p
            LEFT JOIN product_views pv ON pv.product_id = p.product_id
            LEFT JOIN product_likes pl ON pl.product_id = p.product_id
            WHERE 1=1";

    $params = [];
    $types  = "";

    if (!empty($keyword)) {
        $sql .= " AND p.product_name LIKE ?";
        $params[] = "%$keyword%";
        $types   .= "s";
    }

    if (!empty($category)) {
        $sql .= " AND p.category_id = ?";
        $params[] = $category;
        $types   .= "i";
    }

    if ($price == "under1") {
        $sql .= " AND p.price < 1000000";
    } elseif ($price == "1to10") {
        $sql .= " AND p.price BETWEEN 1000000 AND 10000000";
    } elseif ($price == "over10") {
        $sql .= " AND p.price > 10000000";
    }

    // GROUP BY trước ORDER BY
    $sql .= " GROUP BY p.product_id";

    if ($sort == "newest") {
        $sql .= " ORDER BY p.created_at DESC";
    } elseif ($sort == "asc") {
        $sql .= " ORDER BY p.price ASC";
    } elseif ($sort == "desc") {
        $sql .= " ORDER BY p.price DESC";
    } elseif ($sort == "views") {
        $sql .= " ORDER BY view_count DESC";
    } elseif ($sort == "likes") {
        $sql .= " ORDER BY like_count DESC";
    }

    $stmt = $this->conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result();
}
    // Hàm đếm lượt xem
    public function countViews($product_id)
    {
        $product_id = (int)$product_id;
        $sql = "SELECT COUNT(*) as total FROM product_views WHERE product_id = $product_id";
        $result = $this->conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['total'];
        }
        return 0;
    }
    // Hàm lấy sản phẩm theo danh mục
    public function getByCategory(int $categoryId, int $limit = 16, int $offset = 0): array
    {
        $sql  = "SELECT * FROM products
             WHERE category_id = ?
             ORDER BY created_at DESC
             LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iii', $categoryId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }
    // Hàm đếm tổng số sản phẩm theo danh mục (dùng cho phân trang)

    public function countByCategory(int $categoryId): int
    {
        $sql    = "SELECT COUNT(*) AS total FROM products WHERE category_id = ?";
        $stmt   = $this->conn->prepare($sql);
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)($result['total'] ?? 0);
    }
}
