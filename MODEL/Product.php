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

    // gỡ product theo ID
    public function delete(int $product_id)
    {
        $stmt = $this->conn->prepare("
        UPDATE {$this->table}
        SET status = 0
        WHERE product_id = ?
    ");

        $stmt->bind_param("i", $product_id);

        return $stmt->execute();
    }
    // lấy sản phẩm top
    public function getFeaturedNFT()
    {
        $stmt = $this->conn->prepare("
        SELECT 
            p.product_id,
            p.product_name,
            p.thumbnail_url,
            p.price,
            p.created_at,
            p.description,
            p.preview_url,
            a.username,
            a.avatar_url
        FROM products p
        JOIN accounts a ON p.account_id = a.account_id
        ORDER BY p.created_at DESC
        LIMIT 1
    ");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // Trim toàn bộ các trường string
            $row['preview_url']    = trim($row['preview_url'] ?? '');
            $row['thumbnail_url']  = trim($row['thumbnail_url'] ?? '');
            $row['product_name']   = trim($row['product_name'] ?? '');
            $row['username']       = trim($row['username'] ?? '');

            if (
                !str_starts_with($row['thumbnail_url'], 'http') &&
                !str_starts_with($row['thumbnail_url'], './')
            ) {
                $row['thumbnail_url'] = './' . $row['thumbnail_url'];
            }
        }

        return $row;
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
    // lấy sản phẩm mới thêm
    public function getRecentActivities($limit = 5)
    {
        $sql = "
            SELECT 
                p.product_id,
                p.product_name,
                p.thumbnail_url,
                p.created_at,
                a.username
            FROM products p
            JOIN accounts a 
                ON p.account_id = a.account_id
            ORDER BY p.created_at DESC
            LIMIT ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }
    // Lấy sản phẩm theo account
    public function getByAccountId($account_id)
    {
        $sql = "
        SELECT *
        FROM products
        WHERE account_id = ?
        ORDER BY created_at DESC
    ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $account_id);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        // fallback nếu server không có mysqlnd
        $data = [];
        $stmt->bind_result(
            $product_id,
            $product_name,
            $description,
            $category_id,
            $account_id,
            $thumbnail_url,
            $price,
            $created_at,
            $preview_url
        );

        while ($stmt->fetch()) {
            $data[] = [
                'product_id'    => $product_id,
                'product_name'  => $product_name,
                'description'   => $description,
                'category_id'   => $category_id,
                'account_id'    => $account_id,
                'thumbnail_url' => $thumbnail_url,
                'price'         => $price,
                'created_at'    => $created_at,
                'preview_url'   => $preview_url,
            ];
        }

        return $data;
    }

    // Đếm sản phẩm
    public function countByAccountId($account_id)
    {
        $stmt = $this->conn->prepare("
        SELECT COUNT(*) total
        FROM products
        WHERE account_id = ?
    ");

        $stmt->bind_param("i", $account_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }
    // khôi phục sản phẩm
    public function restore(int $product_id)
    {
        $stmt = $this->conn->prepare("
        UPDATE {$this->table}
        SET status = 1
        WHERE product_id = ?
    ");

        $stmt->bind_param("i", $product_id);

        return $stmt->execute();
    }
    // Tổng sản phẩm
    public function countAll()
    {
        $sql = "
        SELECT COUNT(*) total
        FROM products
        WHERE status = 1
    ";

        return $this->conn->query($sql)->fetch_assoc()['total'];
    }

    // Top sản phẩm bán chạy
    public function getTopSellingProducts($limit = 5)
    {
        $sql = "
        SELECT
            p.product_id,
            p.product_name,
            c.category_name,
            SUM(oi.quantity) total_sold,
            SUM(oi.quantity * p.price) revenue

        FROM products p

        JOIN order_items oi
            ON p.product_id = oi.product_id

        LEFT JOIN categories c
            ON p.category_id = c.category_id

        GROUP BY p.product_id

        ORDER BY revenue DESC

        LIMIT ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }
    public function countSellerProducts($accountId)
    {
        $stmt = $this->conn->prepare("
        SELECT COUNT(*) total
        FROM products
        WHERE account_id = ?
        AND status = 1
    ");

        $stmt->bind_param("i", $accountId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }
    public function searchSellerProducts($accountId, $keyword)
    {
        $stmt = $this->conn->prepare("
        SELECT *
        FROM products
        WHERE account_id = ?
        AND status = 1
        AND product_name LIKE ?
        ORDER BY created_at DESC
    ");

        $keyword = "%{$keyword}%";

        $stmt->bind_param(
            "is",
            $accountId,
            $keyword
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function countSellerViews($accountId)
    {
        $stmt = $this->conn->prepare("
        SELECT COUNT(v.view_id) total
        FROM product_views v

        INNER JOIN products p
            ON v.product_id = p.product_id

        WHERE p.account_id = ?
    ");

        $stmt->bind_param("i", $accountId);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    }
    public function getSellerTopProducts($sellerId, $limit = 5)
    {
        $stmt = $this->conn->prepare("
        SELECT
            p.product_name,
            SUM(oi.quantity) total_sold,
            SUM(oi.quantity * p.price) revenue

        FROM products p

        INNER JOIN order_items oi
            ON p.product_id = oi.product_id

        WHERE p.account_id = ?

        GROUP BY p.product_id

        ORDER BY total_sold DESC

        LIMIT ?
    ");

        $stmt->bind_param("ii", $sellerId, $limit);
        $stmt->execute();

        return $stmt->get_result();
    }
    public function getSellerProductSaleStats($accountId)
    {
        $stmt = $this->conn->prepare("
        SELECT
            COUNT(DISTINCT p.product_id) total_products,

            COUNT(
                DISTINCT CASE
                    WHEN oi.product_id IS NOT NULL
                    THEN p.product_id
                END
            ) sold_products

        FROM products p

        LEFT JOIN order_items oi
            ON p.product_id = oi.product_id

        WHERE p.account_id = ?
        AND p.status = 1
    ");

        $stmt->bind_param("i", $accountId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}
