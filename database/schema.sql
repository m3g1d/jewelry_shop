-- Database Schema for Jewelry Shop 
-- Based on User Provided T-SQL Schema

DROP DATABASE IF EXISTS jewelry_shop;
CREATE DATABASE jewelry_shop;
USE jewelry_shop;

-- 1. TABELAT  (PA FOREIGN KEYS)

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE material (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- 2. TABELAT KRYESORE

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('Customer', 'Admin', 'Warehouse') NOT NULL DEFAULT 'Customer',
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    account_status ENUM('Active', 'Blocked') NOT NULL DEFAULT 'Active',
    failed_attempts TINYINT DEFAULT 0,
    block_date DATETIME NULL,
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    street_address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255) NULL, -- Added to support images as per previous code
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

CREATE TABLE jewelry_details (
    product_id INT PRIMARY KEY,
    material_id INT NOT NULL,
    weight_grams DECIMAL(8,3) NOT NULL,
    carat TINYINT NOT NULL,
    gemstone_type VARCHAR(100) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES material(material_id)
);

-- 3. SHPORTA DHE POROSITË

CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE cart_items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES cart(cart_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    shipping_address_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    order_status ENUM('New', 'Processing', 'Paid', 'Shipped', 'Delivered', 'Cancelled') NOT NULL DEFAULT 'New',
    total_amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES users(user_id),
    FOREIGN KEY (shipping_address_id) REFERENCES addresses(address_id)
);

CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    price_at_order DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- 4. PAGESAT, DËRGESAT DHE LOGS

CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    method ENUM('PayPal', 'Credit Card', 'Bank Transfer') NOT NULL,
    transaction_status ENUM('Success', 'Pending', 'Failed') NOT NULL,
    external_transaction_id VARCHAR(255) NOT NULL,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

CREATE TABLE shipments (
    shipment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    warehouse_user_id INT NOT NULL,
    tracking_number VARCHAR(100) NOT NULL,
    shipping_date DATE NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (warehouse_user_id) REFERENCES users(user_id)
);

CREATE TABLE stock_logs (
    movement_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity_change INT NOT NULL,
    reason ENUM('Restock', 'Sale', 'Return', 'Damaged') NOT NULL,
    movement_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE product_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_main BOOLEAN NOT NULL DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- 5. TRIGGERS & PROCEDURES (MySQL Syntax)

DELIMITER //

CREATE TRIGGER trg_update_stock_after_order
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock_quantity = stock_quantity - NEW.quantity
    WHERE product_id = NEW.product_id;
END//

CREATE PROCEDURE sp_create_order(
    IN p_customer_id INT, 
    IN p_shipping_address_id INT, 
    IN p_total_amount DECIMAL(10,2), 
    IN p_external_transaction_id VARCHAR(255), 
    IN p_payment_method VARCHAR(50),
    OUT p_new_order_id INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    -- 1. Create Order
    INSERT INTO orders (customer_id, shipping_address_id, total_amount, order_status, order_date)
    VALUES (p_customer_id, p_shipping_address_id, p_total_amount, 'Processing', NOW());

    SET p_new_order_id = LAST_INSERT_ID();

    -- 2. Record Payment
    INSERT INTO payments (order_id, external_transaction_id, method, transaction_status, payment_date)
    VALUES (p_new_order_id, p_external_transaction_id, p_payment_method, 'Success', NOW());

    COMMIT;
END//

DELIMITER ;

-- 6. INDEXES (B.5)
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_orders_customer ON orders(customer_id);
CREATE INDEX idx_orders_status ON orders(order_status);
CREATE INDEX idx_users_email ON users(email);

-- 7. FUNCTIONS (B.3)
DELIMITER //

CREATE FUNCTION fn_get_total_customer_spent(f_customer_id INT) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE total DECIMAL(10,2);
    SELECT IFNULL(SUM(total_amount), 0) INTO total 
    FROM orders 
    WHERE customer_id = f_customer_id AND order_status != 'Cancelled';
    RETURN total;
END//

DELIMITER ;
