-- Dal Tokki Coffee Database Schema
-- Import this file into phpMyAdmin or MySQL

CREATE DATABASE IF NOT EXISTS dal_tokki_coffee;
USE dal_tokki_coffee;

-- Users table for customer accounts
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    is_bestseller BOOLEAN DEFAULT FALSE,
    is_available BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    pickup_time DATETIME,
    special_instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    special_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Cart sessions (for guest users before login)
CREATE TABLE cart_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_session_product (session_id, product_id)
);

-- Insert default categories
INSERT INTO categories (name, description, display_order) VALUES
('Signature Drinks', 'Our special house-made beverages', 1),
('Coffee', 'Premium coffee selections', 2),
('Tea', 'Fresh brewed teas', 3),
('Pastries', 'Fresh baked goods', 4),
('Snacks', 'Light bites and treats', 5);

-- Insert current best sellers and more products
INSERT INTO products (category_id, name, description, price, image_url, is_bestseller, display_order) VALUES
-- Best Sellers (Signature Drinks)
(1, 'Blueberry Lemon Soda', 'Crafted for refreshment, finished with a sparkle.', 85.00, 'assets/images/Blueberry~lemon~soda.jpeg', TRUE, 1),
(1, 'Mango Lemon Soda', 'Tropical sweetness meets citrus sparkle—refreshment in every sip.', 90.00, 'assets/images/Mango~lemon~soda.jpeg', TRUE, 2),
(1, 'Wildberry Lemon Black Tea', 'Bold black tea infused with wild berries and lemon—your perfect balance of fruity and strong.', 95.00, 'assets/images/Wildberry lemon black tea.jpeg', TRUE, 3),

-- Additional Coffee Menu (requires login to see)
(2, 'Americano', 'Classic black coffee, bold and smooth', 65.00, NULL, FALSE, 1),
(2, 'Cappuccino', 'Espresso with steamed milk and foam', 75.00, NULL, FALSE, 2),
(2, 'Latte', 'Smooth espresso with steamed milk', 80.00, NULL, FALSE, 3),
(2, 'Mocha', 'Coffee meets chocolate in perfect harmony', 85.00, NULL, FALSE, 4),

-- Tea Selection
(3, 'Earl Grey', 'Classic bergamot-infused black tea', 60.00, NULL, FALSE, 1),
(3, 'Green Tea', 'Fresh and light green tea', 55.00, NULL, FALSE, 2),
(3, 'Chamomile', 'Relaxing herbal tea', 60.00, NULL, FALSE, 3),

-- Pastries
(4, 'Croissant', 'Buttery, flaky French pastry', 45.00, NULL, FALSE, 1),
(4, 'Blueberry Muffin', 'Fresh baked with real blueberries', 50.00, NULL, FALSE, 2),
(4, 'Chocolate Chip Cookie', 'Warm, gooey chocolate chip cookie', 35.00, NULL, FALSE, 3),

-- Snacks
(5, 'Banana Bread', 'Moist and flavorful homemade banana bread', 40.00, NULL, FALSE, 1),
(5, 'Granola Bar', 'Healthy oats and nuts energy bar', 30.00, NULL, FALSE, 2);

-- Create admin user (password: admin123 - hashed)
INSERT INTO users (username, email, password, full_name, phone) VALUES
('admin', 'admin@daltokki.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', '09123456789');

-- Create indexes for better performance
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_bestseller ON products(is_bestseller);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_cart_session ON cart_sessions(session_id);
