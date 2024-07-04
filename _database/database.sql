CREATE DATABASE IF NOT EXISTS `tecweb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `tecweb`;

DROP DATABASE mediashop2000;
CREATE DATABASE IF NOT EXISTS mediashop2000;
USE mediashop2000;

-- Create the Category table
CREATE TABLE Category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

-- Create the Product table
CREATE TABLE Product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(50) NOT NULL,
    brand VARCHAR(50),
    color TEXT,
    price DECIMAL(20,2),
    description VARCHAR(255)
);

-- Create the CategoryProduct table
CREATE TABLE CategoryProduct (
    category_id INT,
    product_id INT,
    PRIMARY KEY (category_id, product_id),
    FOREIGN KEY (category_id) REFERENCES Category(category_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);

-- Create the PriceCut table
CREATE TABLE PriceCut (
    discountInPercentage TINYINT,
    new_price DECIMAL(20,2),
    product_id INT,
    PRIMARY KEY (discountInPercentage, product_id),
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);

-- Create the ProductImage table
CREATE TABLE ProductImage (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    product_id INT,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);

-- Create the User table (can expand role into table)
CREATE TABLE User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(50),
    role VARCHAR(5) NOT NULL DEFAULT 'user' CHECK(role IN ('admin', 'user')),
    creation_datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
);
-- Create the Orderstable
CREATE TABLE Orders(
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    total DECIMAL(20,2),
    order_datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    name VARCHAR(50),
    surname VARCHAR(50),
    email VARCHAR(50),
    phone VARCHAR(50),
    address VARCHAR(50),
    city VARCHAR(50),
    zip VARCHAR(50),
    country VARCHAR(50) DEFAULT 'Italia',
    card_number VARCHAR(50),
    card_expiry VARCHAR(50),
    card_cvv VARCHAR(50),
    note VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);
-- Create the OrderDetail table
CREATE TABLE OrderDetail (
    quantity INT,
    order_id INT,
    product_id INT,
    color VARCHAR(10),
    PRIMARY KEY (order_id, product_id, color),
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id)
);
-- Create the FeedBack table
CREATE TABLE Feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    star_rating TINYINT,
    description VARCHAR(255) NOT NULL,
    product_id INT,
    user_id INT,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

INSERT INTO Category (category_id, category_name) VALUES (1, 'Cellulare');
INSERT INTO Category (category_id, category_name) VALUES (2, 'Computer');
INSERT INTO Category (category_id, category_name) VALUES (3, 'Game Station');
INSERT INTO Category (category_id, category_name) VALUES (4, 'Smartwatch');
INSERT INTO Category (category_id, category_name) VALUES (5, 'Fotocamera');

INSERT INTO User(user_id, username, password, email, role) VALUES (1,'admin', '$2y$10$rsiFtq97A7W58Fs7ZX27nelrK9qItNqnNicgGjWlPpHg1tfgDaMB2','admin@admin.com', 'admin');
INSERT INTO User(user_id, username, password, email, role) VALUES (2,'user', '$2y$10$/XHfArdPL0gGkMEpKMfmde4sw5XfdqW/hoqBHKQi9N4rjbEw7Xb06','user@user.com', 'user');
INSERT INTO User(user_id, username, password, email, role) VALUES (3,'test', '$2y$10$/XHfArdPL0gGkMEpKMfmde4sw5XfdqW/hoqBHKQi9N4rjbEw7Xb06','test@user.com', 'user');


INSERT INTO Product (product_name, brand, color, price, description)
VALUES
    ('iPhone 4S', 'Apple', 'Black', 199.99, 'Old generation iPhone'),
    ('Samsung Galaxy S3', 'Samsung', 'White', 149.99, 'Android smartphone'),
    ('Dell Inspiron 15', 'Dell', 'Silver', 499.99, 'Basic laptop for everyday use'),
    ('PlayStation 3', 'Sony', 'Black', 299.99, 'Previous generation game console'),
    ('Fitbit Versa', 'Fitbit', 'Black', 129.99, 'Fitness smartwatch'),
    ('Canon PowerShot SX240', 'Canon', 'Black', 199.99, 'Compact digital camera'),
    ('Nokia Lumia 920', 'Nokia', 'Yellow', 99.99, 'Windows Phone'),
    ('HP Pavilion Desktop', 'HP', 'Black', 699.99, 'Desktop computer for home office'),
    ('Nintendo Wii', 'Nintendo', 'White', 249.99, 'Family game console'),
    ('Garmin Forerunner 405', 'Garmin', 'Black', 149.99, 'GPS running watch'),
    ('Sony Cyber-shot DSC-WX50', 'Sony', 'Silver', 179.99, 'Point-and-shoot digital camera'),
    ('Acer Aspire One', 'Acer', 'Blue', 299.99, 'Netbook computer'),
    ('Xbox 360', 'Microsoft', 'Black', 199.99, 'Game console with Kinect sensor'),
    ('Pebble Smartwatch', 'Pebble', 'Black', 99.99, 'Early model smartwatch'),
    ('Canon EOS 550D', 'Canon', 'Black', 699.99, 'Digital SLR camera'),
    ('Apple MacBook Air', 'Apple', 'Silver', 999.99, 'Lightweight laptop for travelers'),
    ('Nintendo DS Lite', 'Nintendo', 'Blue', 149.99, 'Handheld game console'),
    ('Samsung Gear Fit', 'Samsung', 'Black', 149.99, 'Fitness band with OLED display'),
    ('Olympus PEN E-PL1', 'Olympus', 'Silver', 499.99, 'Micro Four Thirds camera'),
    ('Alienware Gaming Laptop', 'Alienware', 'Black', 1499.99, 'High-performance gaming laptop');

INSERT INTO CategoryProduct (category_id, product_id)
VALUES
    (1, 1),    -- iPhone 4S
    (1, 2),    -- Samsung Galaxy S3
    (2, 3),    -- Dell Inspiron 15
    (3, 4),    -- PlayStation 3
    (4, 5),    -- Fitbit Versa
    (5, 6),    -- Canon PowerShot SX240
    (1, 7),    -- Nokia Lumia 920
    (2, 8),    -- HP Pavilion Desktop
    (3, 9),    -- Nintendo Wii
    (4, 10),   -- Garmin Forerunner 405
    (5, 11),   -- Sony Cyber-shot DSC-WX50
    (2, 12),   -- Acer Aspire One
    (3, 13),   -- Xbox 360
    (4, 14),   -- Pebble Smartwatch
    (5, 15),   -- Canon EOS 550D
    (2, 16),   -- Apple MacBook Air
    (3, 17),   -- Nintendo DS Lite
    (4, 18),   -- Samsung Gear Fit
    (5, 19),   -- Olympus PEN E-PL1
    (2, 20);   -- Alienware Gaming Laptop

INSERT INTO ProductImage (url, product_id)
VALUES
    ('upload/Apple-iPhone-4S.jpg', 1),
    ('upload/Galaxy 3s.jpg', 2),
    ('upload/Dell-inspiron15.jpg', 3),
    ('upload/Ps3.jpg', 4),
    ('upload/fitbit-versa-2-wifi-gris.jpg', 5),
    ('upload/Canon PowerShot SX240.jpg', 6),
    ('upload/nokia Lumia 920.jpg', 7),
    ('upload/HP Pavilion Desktop.jpg', 8),
    ('upload/1200px-Wii-console.jpg', 9),
    ('upload/Garmin Forerunner 405.jpg', 10),
    ('upload/Sony Cyber-shot DSC-WX50.jpg', 11),
    ('upload/Acer Aspire One.jpg', 12),
    ('upload/Xbox 360.jpg', 13),
    ('upload/Pebble Smartwatch_.jpg', 14),
    ('upload/Canon EOS 550D.jpg', 15),
    ('upload/Apple MacBook Air.jpg', 16),
    ('upload/nintendo ds lite.jpg', 17),
    ('upload/Samsung Gear Fit.jpg', 18),
    ('upload/Olympus PEN E-PL1.jpg', 19),
    ('upload/Alienware Gaming Lapto.jpg', 20);
    
INSERT INTO Feedback (star_rating, description, product_id, user_id)
VALUES
    (5, 'Great phone, very fast and reliable.', 1, 2),
    (4, 'Good camera quality, but battery life could be better.', 2, 2),
    (3, 'Decent laptop for the price, but not very powerful.', 3, 2),
    (5, 'Love the games on this console, great for parties.', 4, 2),
    (4, 'Nice design and comfortable to wear.', 5, 2),
    (5, 'Excellent image quality and easy to use.', 6, 2),
    (3, 'Not a fan of the Windows Phone OS, but the phone itself is good.', 7, 2),
    (4, 'Fast computer with plenty of storage space.', 8, 2),
    (5, 'Fun for the whole family, especially with the motion controls.', 9, 2),
    (4, 'Accurate GPS tracking and good battery life.', 10, 2),
    (5, 'Great camera for everyday use, very portable.', 11, 2),
    (3, 'Slow netbook with limited capabilities.', 12, 2),
    (4, 'Fun games and easy to use interface.', 13, 2),
    (5, 'Simple and effective smartwatch, great for notifications.', 14, 2),
    (5, 'Amazing photos and video quality, very versatile.', 15, 2),
    (4, 'Lightweight and long battery life, perfect for travel.', 16, 2),
    (3, 'Not many games available, but good for long trips.', 17, 2),
    (4, 'Comfortable to wear and easy to read display.', 18, 2),
    (5, 'Great image quality and interchangeable lenses.', 19, 2),
    (5, 'High-performance laptop with great graphics.', 20, 2);
