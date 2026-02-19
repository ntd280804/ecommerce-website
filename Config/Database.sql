-- =========================================
-- DATABASE
-- =========================================

DROP DATABASE IF EXISTS pokemon_store;
CREATE DATABASE pokemon_store 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE pokemon_store;

SET FOREIGN_KEY_CHECKS = 0;

-- =========================================
-- USERS
-- =========================================

CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    password VARCHAR(255),
    role ENUM('admin','staff','user') DEFAULT 'user',
    status ENUM('active','inactive','banned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================
-- CATEGORIES
-- =========================================

CREATE TABLE categories (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE INDEX idx_category_slug ON categories(slug);

-- =========================================
-- PRODUCTS
-- =========================================

CREATE TABLE products (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    summary TEXT,
    description TEXT,
    category_id BIGINT,
    images TEXT,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE product_variants (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    sku VARCHAR(100) UNIQUE NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    reserved_quantity INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    status ENUM('active','inactive') DEFAULT 'active',
    version INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    CHECK (stock_quantity >= 0),
    CHECK (reserved_quantity >= 0)
) ENGINE=InnoDB;

CREATE INDEX idx_variant_product ON product_variants(product_id);

-- =========================================
-- CART
-- =========================================

CREATE TABLE carts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    status ENUM('active','converted','abandoned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE INDEX idx_cart_user ON carts(user_id);

CREATE TABLE cart_items (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    cart_id BIGINT NOT NULL,
    variant_id BIGINT NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    UNIQUE KEY uk_cart_variant (cart_id, variant_id),
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id),
    CHECK (qty > 0)
) ENGINE=InnoDB;

-- =========================================
-- ORDERS
-- =========================================

CREATE TABLE orders (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id BIGINT NOT NULL,
    receiver_name VARCHAR(255) NOT NULL,
    receiver_phone VARCHAR(20) NOT NULL,
    receiver_address TEXT NOT NULL,
    status ENUM('pending','processing','shipping','completed','cancelled','refunded') DEFAULT 'pending',
    subtotal DECIMAL(15,2) NOT NULL,
    grand_total DECIMAL(15,2) NOT NULL,
    payment_status ENUM('unpaid','paid','failed','refunded') DEFAULT 'unpaid',
    payment_method ENUM('online','cod') DEFAULT 'cod',
    payment_transaction_id VARCHAR(100) UNIQUE,
    expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_order_status ON orders(status);
CREATE INDEX idx_order_expires ON orders(expires_at);

CREATE TABLE order_items (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT NOT NULL,
    variant_id BIGINT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    sku VARCHAR(100) NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    qty INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id),
    CHECK (qty > 0)
) ENGINE=InnoDB;

-- =========================================
-- INVENTORY LOGS
-- =========================================

CREATE TABLE inventory_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    variant_id BIGINT NOT NULL,
    action_type ENUM('allocate','release','deduct_sold','restock_cancel','import') NOT NULL,
    quantity_change INT NOT NULL,
    stock_after INT NOT NULL,
    reserved_after INT NOT NULL,
    reference_id BIGINT,
    note VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;


DROP PROCEDURE IF EXISTS sp_release_expired_orders;
DELIMITER //

CREATE PROCEDURE sp_release_expired_orders()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    /* =====================================================
       1️⃣ Lock expired + unpaid + pending orders
    ===================================================== */
    SELECT id
    FROM orders
    WHERE payment_status = 'unpaid'
      AND status = 'pending'
      AND expires_at <= NOW()
    FOR UPDATE;

    /* =====================================================
       2️⃣ Lock related variants in deterministic order
    ===================================================== */
    SELECT pv.id
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN product_variants pv ON oi.variant_id = pv.id
    WHERE o.payment_status = 'unpaid'
      AND o.status = 'pending'
      AND o.expires_at <= NOW()
    ORDER BY pv.id ASC
    FOR UPDATE;

    /* =====================================================
       3️⃣ Release reserved stock
    ===================================================== */
    UPDATE product_variants pv
    JOIN order_items oi ON pv.id = oi.variant_id
    JOIN orders o ON o.id = oi.order_id
    SET pv.reserved_quantity = pv.reserved_quantity - oi.qty,
        pv.version = pv.version + 1
    WHERE o.payment_status = 'unpaid'
      AND o.status = 'pending'
      AND o.expires_at <= NOW();

    /* =====================================================
       4️⃣ Write inventory log
    ===================================================== */
    INSERT INTO inventory_logs (
        variant_id,
        action_type,
        quantity_change,
        stock_after,
        reserved_after,
        reference_id,
        note
    )
    SELECT
        pv.id,
        'release',
        -oi.qty,
        pv.stock_quantity,
        pv.reserved_quantity,
        o.id,
        'Order expired auto-release'
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN product_variants pv ON oi.variant_id = pv.id
    WHERE o.payment_status = 'unpaid'
      AND o.status = 'pending'
      AND o.expires_at <= NOW();

    /* =====================================================
       5️⃣ Mark orders as cancelled
    ===================================================== */
    UPDATE orders
    SET status = 'cancelled'
    WHERE payment_status = 'unpaid'
      AND status = 'pending'
      AND expires_at <= NOW();

    COMMIT;
END //

DELIMITER ;


-- =====================================================
-- STORED PROCEDURES
-- =====================================================

DELIMITER //

-- =====================================================
-- CREATE ORDER (Safe + Ownership + Expire)
-- =====================================================

DROP PROCEDURE IF EXISTS sp_create_order;
DELIMITER //

CREATE PROCEDURE sp_create_order(
    IN p_user_id BIGINT,
    IN p_cart_id BIGINT,
    IN p_payment_method ENUM('online', 'cod'),
    IN p_receiver_name VARCHAR(255),
    IN p_receiver_phone VARCHAR(20),
    IN p_receiver_address TEXT,
    OUT p_order_id BIGINT
)
BEGIN
    DECLARE v_total DECIMAL(15,2);
    DECLARE v_order_number VARCHAR(50);
    DECLARE v_order_status VARCHAR(20) DEFAULT 'pending';
    DECLARE v_expires_at DATETIME;  -- ✅ thêm biến này

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF NOT EXISTS (
        SELECT 1 FROM carts
        WHERE id = p_cart_id AND user_id = p_user_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cart does not belong to user';
    END IF;

    -- 1. Lock variants
    SELECT pv.id FROM product_variants pv
    JOIN cart_items ci ON pv.id = ci.variant_id
    WHERE ci.cart_id = p_cart_id
    ORDER BY pv.id ASC
    FOR UPDATE;

    -- 2. Check stock
    IF EXISTS (
        SELECT 1 FROM cart_items ci
        JOIN product_variants pv ON ci.variant_id = pv.id
        WHERE ci.cart_id = p_cart_id 
        AND (pv.stock_quantity - pv.reserved_quantity) < ci.qty
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Không đủ hàng trong kho';
    END IF;

    -- 3. Calculate total
    SELECT SUM(pv.price * ci.qty) INTO v_total
    FROM cart_items ci
    JOIN product_variants pv ON ci.variant_id = pv.id
    WHERE ci.cart_id = p_cart_id;

    -- 4. Set status + expire logic ✅ FIX CHỖ NÀY
    IF p_payment_method = 'cod' THEN
        SET v_order_status = 'processing';
        SET v_expires_at = NULL; -- COD không expire
    ELSE
        SET v_order_status = 'pending';
        SET v_expires_at = DATE_ADD(NOW(), INTERVAL 30 MINUTE);
    END IF;

    -- 5. Generate order number
    SET v_order_number = CONCAT(
        IF(p_payment_method='cod','COD-','ONL-'),
        DATE_FORMAT(NOW(), '%y%m%d%H%i'),
        '-',
        UUID_SHORT()
    );

    -- 6. Create order (sửa expires_at)
    INSERT INTO orders (
        order_number,
        user_id,
        receiver_name,
        receiver_phone,
        receiver_address,
        subtotal,
        grand_total,
        status,
        payment_method,
        expires_at
    )
    VALUES (
        v_order_number,
        p_user_id,
        p_receiver_name,
        p_receiver_phone,
        p_receiver_address,
        v_total,
        v_total,
        v_order_status,
        p_payment_method,
        v_expires_at   -- ✅ dùng biến thay vì DATE_ADD trực tiếp
    );

    SET p_order_id = LAST_INSERT_ID();

    -- 7. Inventory
    IF p_payment_method = 'online' THEN

        UPDATE product_variants pv
        JOIN cart_items ci ON pv.id = ci.variant_id
        SET pv.reserved_quantity = pv.reserved_quantity + ci.qty
        WHERE ci.cart_id = p_cart_id;

        INSERT INTO inventory_logs (
            variant_id, action_type, quantity_change,
            stock_after, reserved_after, reference_id, note
        )
        SELECT
            pv.id,
            'allocate',
            ci.qty,
            pv.stock_quantity,
            pv.reserved_quantity,
            p_order_id,
            'Giữ hàng chờ thanh toán Online'
        FROM cart_items ci
        JOIN product_variants pv ON ci.variant_id = pv.id
        WHERE ci.cart_id = p_cart_id;

    ELSE

        UPDATE product_variants pv
        JOIN cart_items ci ON pv.id = ci.variant_id
        SET pv.stock_quantity = pv.stock_quantity - ci.qty
        WHERE ci.cart_id = p_cart_id;

        UPDATE product_variants pv
        JOIN cart_items ci ON pv.id = ci.variant_id
        SET pv.reserved_quantity = pv.reserved_quantity + ci.qty
        WHERE ci.cart_id = p_cart_id;

        INSERT INTO inventory_logs (
            variant_id, action_type, quantity_change,
            stock_after, reserved_after, reference_id, note
        )
        SELECT
            pv.id,
            'deduct_sold',
            -ci.qty,
            pv.stock_quantity,
            pv.reserved_quantity,
            p_order_id,
            'Trừ kho trực tiếp cho đơn COD'
        FROM cart_items ci
        JOIN product_variants pv ON ci.variant_id = pv.id
        WHERE ci.cart_id = p_cart_id;

    END IF;

    -- 8. Create order items
    INSERT INTO order_items (
        order_id, variant_id, product_name, sku, price, qty
    )
    SELECT
        p_order_id,
        pv.id,
        p.name,
        pv.sku,
        pv.price,
        ci.qty
    FROM cart_items ci
    JOIN product_variants pv ON ci.variant_id = pv.id
    JOIN products p ON pv.product_id = p.id
    WHERE ci.cart_id = p_cart_id;

    UPDATE carts
    SET status = 'converted'
    WHERE id = p_cart_id;

    COMMIT;
END //


-- =====================================================
-- CONFIRM PAYMENT (Idempotent + Integrity Safe)
-- =====================================================

DROP PROCEDURE IF EXISTS sp_confirm_payment;
DELIMITER //

CREATE PROCEDURE sp_confirm_payment(
    IN p_order_id BIGINT,
    IN p_transaction_id VARCHAR(100)
)
proc: BEGIN

    DECLARE v_payment_status VARCHAR(20);
    DECLARE v_order_status VARCHAR(20);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    /* =====================================================
       1️⃣ Lock order row (Idempotency + Concurrency Guard)
    ===================================================== */
    SELECT payment_status, status
    INTO v_payment_status, v_order_status
    FROM orders
    WHERE id = p_order_id
    FOR UPDATE;

    /* =====================================================
       2️⃣ Validate existence
    ===================================================== */
    IF v_payment_status IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Order not found';
    END IF;

    /* =====================================================
       3️⃣ Idempotency check
    ===================================================== */
    IF v_payment_status = 'paid' THEN
        COMMIT;
        LEAVE proc;
    END IF;

    /* =====================================================
       4️⃣ Guard: Order must still be pending
       (Prevent payment after expire cancellation)
    ===================================================== */
    IF v_order_status != 'pending' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Order no longer valid for payment';
    END IF;

    /* =====================================================
       5️⃣ Lock variants in deterministic order
    ===================================================== */
    SELECT pv.id
    FROM order_items oi
    JOIN product_variants pv ON oi.variant_id = pv.id
    WHERE oi.order_id = p_order_id
    ORDER BY pv.id ASC
    FOR UPDATE;

    /* =====================================================
       6️⃣ Integrity check: reserved must be enough
    ===================================================== */
    IF EXISTS (
        SELECT 1
        FROM order_items oi
        JOIN product_variants pv ON oi.variant_id = pv.id
        WHERE oi.order_id = p_order_id
          AND pv.reserved_quantity < oi.qty
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Data Integrity Error: Reserved quantity mismatch';
    END IF;

    /* =====================================================
       7️⃣ Deduct stock + release reserve
    ===================================================== */
    UPDATE product_variants pv
    JOIN order_items oi ON pv.id = oi.variant_id
    SET pv.stock_quantity = pv.stock_quantity - oi.qty,
        pv.reserved_quantity = pv.reserved_quantity - oi.qty,
        pv.version = pv.version + 1
    WHERE oi.order_id = p_order_id;

    /* =====================================================
       8️⃣ Inventory log
    ===================================================== */
    INSERT INTO inventory_logs (
        variant_id,
        action_type,
        quantity_change,
        stock_after,
        reserved_after,
        reference_id,
        note
    )
    SELECT
        pv.id,
        'deduct_sold',
        -oi.qty,
        pv.stock_quantity,
        pv.reserved_quantity,
        p_order_id,
        CONCAT('Payment Success: ', p_transaction_id)
    FROM order_items oi
    JOIN product_variants pv ON oi.variant_id = pv.id
    WHERE oi.order_id = p_order_id;

    /* =====================================================
       9️⃣ Update order state
    ===================================================== */
    UPDATE orders
    SET payment_status = 'paid',
        status = 'processing',
        payment_transaction_id = p_transaction_id
    WHERE id = p_order_id;

    COMMIT;

END proc //

DELIMITER ;


INSERT INTO categories (name, slug, status) VALUES
('Electronics', 'electronics', 'active'),
('Books', 'books', 'active'),
('Fashion', 'fashion', 'active'),
('Sports', 'sports', 'active'),
('Technology', 'technology', 'active');


/* ========================
   2️⃣ ADMIN + USER
======================== */

INSERT INTO users (name, email, password, role, status) VALUES
('System Admin', 'admin@pokemonstore.com', '123456', 'admin', 'active'),
('Test User', 'user@test.com', '123456', 'user', 'active');


/* ========================
   3️⃣ PRODUCTS + VARIANTS
======================== */

/* ----- Product 1: Bluetooth Headphones ----- */
INSERT INTO products (name, slug, category_id, status)
VALUES ('Bluetooth Headphones X100', 'bluetooth-headphones-x100', 1, 'active');

SET @p1 = LAST_INSERT_ID();

INSERT INTO product_variants (product_id, name, sku, price, stock_quantity)
VALUES
(@p1, 'Black Edition', 'X100-BLACK', 990000, 50),
(@p1, 'White Edition', 'X100-WHITE', 990000, 30);


/* ----- Product 2: Backend Mastery Guide ----- */
INSERT INTO products (name, slug, category_id, status)
VALUES ('Backend Mastery Guide', 'backend-mastery-guide', 2, 'active');

SET @p2 = LAST_INSERT_ID();

INSERT INTO product_variants (product_id, name, sku, price, stock_quantity)
VALUES
(@p2, 'Standard Version', 'BOOK-BACKEND-001', 350000, 100);


/* ----- Product 3: Pro Run Sports Shoes ----- */
INSERT INTO products (name, slug, category_id, status)
VALUES ('Pro Run Sports Shoes', 'pro-run-sports-shoes', 4, 'active');

SET @p3 = LAST_INSERT_ID();

INSERT INTO product_variants (product_id, name, sku, price, stock_quantity)
VALUES
(@p3, 'Size 42', 'PRORUN-42', 1200000, 20),
(@p3, 'Size 43', 'PRORUN-43', 1200000, 25);


/* =====================================================
   DONE
===================================================== */

