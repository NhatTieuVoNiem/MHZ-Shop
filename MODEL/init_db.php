<?php
include 'connect.php';

// Danh sách câu lệnh tạo bảng
$tables = [
    "CREATE TABLE IF NOT EXISTS roles (
        role_id INT AUTO_INCREMENT PRIMARY KEY,
        role_name VARCHAR(50) NOT NULL UNIQUE
    )",

    "CREATE TABLE IF NOT EXISTS accounts (
        account_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        avatar_url VARCHAR(255),
        cover_url VARCHAR(255),
        role_id INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL,
        is_deleted TINYINT(1) DEFAULT 0,
        CONSTRAINT fk_account_role FOREIGN KEY (role_id) REFERENCES roles(role_id)
    )",

    "CREATE TABLE IF NOT EXISTS genders (
        gender_id INT AUTO_INCREMENT PRIMARY KEY,
        gender_name VARCHAR(50) NOT NULL UNIQUE
    )",

    "CREATE TABLE IF NOT EXISTS account_profiles (
        profile_id INT AUTO_INCREMENT PRIMARY KEY,
        account_id INT NOT NULL UNIQUE,
        last_name VARCHAR(100),
        middle_name VARCHAR(100),
        first_name VARCHAR(100),
        gender_id INT,
        date_of_birth DATE,
        bio TEXT,
        phone VARCHAR(20),
        CONSTRAINT fk_profile_account FOREIGN KEY (account_id) REFERENCES accounts(account_id),
        CONSTRAINT fk_profile_gender FOREIGN KEY (gender_id) REFERENCES genders(gender_id)
    )",

    "CREATE TABLE IF NOT EXISTS categories (
        category_id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(150) NOT NULL UNIQUE,
        description VARCHAR(500)
    )",

    "CREATE TABLE IF NOT EXISTS products (
        product_id INT AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(255) NOT NULL,
        description TEXT,
        category_id INT,
        account_id INT,
        thumbnail_url VARCHAR(255),
        price DECIMAL(10,2) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        preview_url VARCHAR(255),
        CONSTRAINT fk_product_category FOREIGN KEY (category_id) REFERENCES categories(category_id),
        CONSTRAINT fk_product_account FOREIGN KEY (account_id) REFERENCES accounts(account_id)
    )",

    "CREATE TABLE IF NOT EXISTS product_views (
        view_id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT,
        account_id INT,
        viewed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(product_id),
        FOREIGN KEY (account_id) REFERENCES accounts(account_id)
    )",

    "CREATE TABLE IF NOT EXISTS product_likes (
        like_id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT,
        account_id INT,
        liked_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT unique_like UNIQUE (product_id, account_id),
        FOREIGN KEY (product_id) REFERENCES products(product_id),
        FOREIGN KEY (account_id) REFERENCES accounts(account_id)
    )",

    "CREATE TABLE IF NOT EXISTS orders (
        order_id INT AUTO_INCREMENT PRIMARY KEY,
        account_id INT,
        total_amount DECIMAL(10,2) DEFAULT 0,
        status VARCHAR(50) DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (account_id) REFERENCES accounts(account_id)
    )",

    "CREATE TABLE IF NOT EXISTS order_items (
        order_item_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_id INT,
        quantity INT,
        CONSTRAINT fk_item_order FOREIGN KEY (order_id) REFERENCES orders(order_id),
        CONSTRAINT fk_item_product FOREIGN KEY (product_id) REFERENCES products(product_id),
        CONSTRAINT unique_order_product UNIQUE (order_id, product_id)
    )",

    "CREATE TABLE IF NOT EXISTS carts (
        cart_id INT AUTO_INCREMENT PRIMARY KEY,
        account_id INT NOT NULL UNIQUE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_cart_account FOREIGN KEY (account_id) REFERENCES accounts(account_id)
    )",

    "CREATE TABLE IF NOT EXISTS cart_items (
        cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
        cart_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT DEFAULT 1,
        CONSTRAINT fk_cart_item_cart FOREIGN KEY (cart_id) REFERENCES carts(cart_id),
        CONSTRAINT fk_cart_item_product FOREIGN KEY (product_id) REFERENCES products(product_id),
        CONSTRAINT unique_cart_product UNIQUE (cart_id, product_id)
    )",

    "CREATE TABLE IF NOT EXISTS product_reviews (
        review_id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        account_id INT NOT NULL,
        rating INT CHECK (rating BETWEEN 1 AND 5),
        comment TEXT,
        reviewed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_review_product FOREIGN KEY (product_id) REFERENCES products(product_id),
        CONSTRAINT fk_review_account FOREIGN KEY (account_id) REFERENCES accounts(account_id),
        CONSTRAINT unique_review UNIQUE (product_id, account_id)
    )"
];

// Thực thi từng câu lệnh
foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Tạo bảng thành công hoặc đã tồn tại.<br>";
    } else {
        echo "Lỗi khi tạo bảng: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
