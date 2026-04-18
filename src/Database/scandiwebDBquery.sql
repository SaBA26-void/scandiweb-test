CREATE DATABASE IF NOT EXISTS scandiweb;
USE scandiweb;

CREATE TABLE IF NOT EXISTS categories (
    category_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    PRIMARY KEY (category_id)
);

CREATE TABLE IF NOT EXISTS products (
    product_id VARCHAR(100) NOT NULL,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(255),
    is_in_stock TINYINT(1) NOT NULL DEFAULT 0,
    description TEXT,
    category_name varchar(255) NOT NULL,
    PRIMARY KEY (product_id),
    CONSTRAINT fk_products_category FOREIGN KEY (category_name)
        REFERENCES categories (name)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS product_images (
    product_image_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(100) NOT NULL,
    product_image_url TEXT NOT NULL,
    INDEX idx_galleries_product (product_id),
    CONSTRAINT fk_galleries_products FOREIGN KEY (product_id)
        REFERENCES products (product_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS currencies (
    currency_id INT UNSIGNED NOT NULL PRIMARY KEY,
    label VARCHAR(32) NOT NULL,
    symbol VARCHAR(8) NOT NULL,
    UNIQUE (label)
);

CREATE TABLE IF NOT EXISTS prices (
    price_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id VARCHAR(100) NOT NULL,
    currency_id INT UNSIGNED NOT NULL,
    amount DECIMAL(12 , 2 ) NOT NULL,
    PRIMARY KEY (price_id),
    UNIQUE KEY uq_product_currency (product_id , currency_id),
    INDEX idx_prices_product (product_id),
    CONSTRAINT fk_prices_product FOREIGN KEY (product_id)
        REFERENCES products (product_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_prices_currency FOREIGN KEY (currency_id)
        REFERENCES currencies (currency_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS attribute_sets (
    attribute_set_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    type VARCHAR(32) NOT NULL,
    PRIMARY KEY (attribute_set_id)
);

CREATE TABLE IF NOT EXISTS attribute_items (
    attribute_item_id VARCHAR(100) NOT NULL,
    attribute_set_id INT UNSIGNED NOT NULL,
    value VARCHAR(255) NOT NULL,
    display_value VARCHAR(255) NOT NULL,
    PRIMARY KEY (attribute_item_id),
    INDEX idx_attr_items_set (attribute_set_id),
    CONSTRAINT fk_attr_items_set FOREIGN KEY (attribute_set_id)
        REFERENCES attribute_sets (attribute_set_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS product_attribute_values (
    pav_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(100) NOT NULL,
    attribute_set_id INT UNSIGNED NOT NULL,
    attribute_item_id VARCHAR(100) NOT NULL,
    INDEX idx_pav_product (product_id),
    CONSTRAINT fk_pav_product FOREIGN KEY (product_id)
        REFERENCES products (product_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pav_attrset FOREIGN KEY (attribute_set_id)
        REFERENCES attribute_sets (attribute_set_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pav_attritem FOREIGN KEY (attribute_item_id)
        REFERENCES attribute_items (attribute_item_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    order_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id VARCHAR(100) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    selected_attributes_json TEXT NOT NULL,
    INDEX idx_order_items_order (order_id),
    INDEX idx_order_items_product (product_id),
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id)
        REFERENCES orders (order_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id)
        REFERENCES products (product_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);