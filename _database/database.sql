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
    product_id INT PRIMARY KEY,
    product_name VARCHAR(50) NOT NULL,
    brand VARCHAR(50),
    color TEXT,
    size TEXT,
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
    status TINYINT,
    order_datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);
-- Create the OrderDetail table
CREATE TABLE OrderDetail (
    quantity INT,
    order_id INT,
    product_id INT,
    PRIMARY KEY (order_id, product_id),
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

INSERT INTO Product (product_id, product_name, brand, color, size, price, description) 
VALUES (1, 'iPhone 12', 'Apple', 'black,white', 'Standard', 299.99, 'A high-end smartphone'),
       (2, 'MacBook Pro', 'Apple', 'silver', 'Standard', 999.99, 'A powerful laptop'),
       (3, 'PlayStation 5', 'Sony', 'black,White', 'Standard', 499.99, 'A next-gen game console'),
       (4, 'Apple Watch', 'Apple', 'silver,black', 'Standard', 199.99, 'A smartwatch with health features');

INSERT INTO CategoryProduct (category_id, product_id) VALUES (1, 1);
INSERT INTO CategoryProduct (category_id, product_id) VALUES (2, 2);
INSERT INTO CategoryProduct (category_id, product_id) VALUES (3, 3);
INSERT INTO CategoryProduct (category_id, product_id) VALUES (4, 4);

INSERT INTO PriceCut (discountInPercentage, new_price, product_id) VALUES (10, 269.99, 1);
INSERT INTO PriceCut (discountInPercentage, new_price, product_id) VALUES (20, 799.99, 2);


INSERT INTO ProductImage (url, product_id) VALUES ('https://picsum.photos/200/200?a=ggj0', 1);
INSERT INTO ProductImage (url, product_id) VALUES ('https://picsum.photos/200/200?a=fgj0', 2);
INSERT INTO ProductImage (url, product_id) VALUES ('https://picsum.photos/200/200?a=fgj1', 3);
INSERT INTO ProductImage (url, product_id) VALUES ('https://picsum.photos/200/200?a=fgj2', 4);


INSERT INTO User(user_id, username, password, email, role) VALUES (1,'admin', '$2y$10$rsiFtq97A7W58Fs7ZX27nelrK9qItNqnNicgGjWlPpHg1tfgDaMB2','admin@admin.com', 'admin');
INSERT INTO User(user_id, username, password, email, role) VALUES (2,'user', '$2y$10$/XHfArdPL0gGkMEpKMfmde4sw5XfdqW/hoqBHKQi9N4rjbEw7Xb06','user@user.com', 'user');
INSERT INTO User(user_id, username, password, email, role) VALUES (3,'test', '$2y$10$/XHfArdPL0gGkMEpKMfmde4sw5XfdqW/hoqBHKQi9N4rjbEw7Xb06','test@user.com', 'user');

INSERT INTO Orders(order_id, total, status, user_id) VALUES (1, 1099.98, 1, 1);
INSERT INTO Orders(order_id, total, status, user_id) VALUES (2, 19.99, 0, 2);
INSERT INTO Orders(order_id, total, status, user_id) VALUES (3, 24.99, 0, 2);
INSERT INTO Orders(order_id, total, status, user_id) VALUES (4, 109.99, 1, 1);

INSERT INTO OrderDetail (quantity, order_id, product_id) VALUES (1, 1, 1);
INSERT INTO OrderDetail (quantity, order_id, product_id) VALUES (2, 1, 2);
INSERT INTO OrderDetail (quantity, order_id, product_id) VALUES (1, 2, 3);
INSERT INTO OrderDetail (quantity, order_id, product_id) VALUES (4, 3, 4);
INSERT INTO OrderDetail (quantity, order_id, product_id) VALUES (2, 4, 1);

INSERT INTO Feedback (feedback_id, star_rating, description, product_id, user_id) 
VALUES (1, 5, 'Great product!', 1, 1),
       (2, 3, 'Not bad, could be better.', 3, 2),
       (3, 5, 'Excellent performance!', 1, 3),
       (4, 4, 'Good quality toy.', 2, 2),
       (5, 5, 'Love this blender, works perfectly.', 4, 1),
       (6, 4, 'Durable and great for games.', 3, 2);