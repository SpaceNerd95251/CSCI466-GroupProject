
--CREATE TABLE users (
 --   id INTEGER AUTO_INCREMENT PRIMARY KEY, 
--    email VARCHAR(100) NOT NULL UNIQUE, 
 --   password VARCHAR(100) NOT NULL, 
 --   isAdmin BOOLEAN DEFAULT false, 
--    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
--);
-- information will be gathered based on state information 
-- collected from the current html session not a logged in user
-- customer could decide later to create an account and log in both will work the same 
CREATE TABLE shoppingCart (
    id INTEGER AUTO_INCREMENT PRIMARY KEY, 
    sessionId VARCHAR(255) NOT NULL, 
  --  userId INTEGER NULL,
    productId INTEGER NOT NULL, 
    quantity INTEGER NOT NULL DEFAULT 1, 
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (productId) REFERENCES products(id),
 --   FOREIGN KEY (userId) REFERENCES users(id),
    UNIQUE(sessionId, productId)
); 

CREATE TABLE products ( 
    id INTEGER AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(50) NOT NULL, 
    description TEXT, 
    price DECIMAL(10, 2) NOT NULL, 
    -- defaults to picture of NO IMAGE AVAILABLE might have to change depending on sizing wait to update this value 
    imageUrl VARCHAR(255) DEFAULT 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg',
    stock INTEGER DEFAULT 0, 
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders ( 
    id INTEGER AUTO_INCREMENT PRIMARY KEY, 
   -- will be generated based on a completed order used as identifying information for the user 
    orderNumber VARCHAR(10) NOT NULL UNIQUE, 
    -- will be used to see find past orders when not logged in
    custEmail VARCHAR(100) NOT NULL, 
    userId INTEGER NULL,
    totalPrice DECIMAL(10, 2), 
    `status` ENUM('Processing', 'Shipping', 'Delivered') DEFAULT 'Processing',
    shippingName VARCHAR(100), 
    streetAddress VARCHAR(100), 
    city VARCHAR(100), 
    state VARCHAR(2), 
    zipcode VARCHAR(5),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   -- FOREIGN KEY (userId) REFERENCES users(id)
);

CREATE TABLE orderItems ( 
    id INTEGER AUTO_INCREMENT PRIMARY KEY, 
    orderId INTEGER NOT NULL, 
    productId INTEGER NOT NULL, 
    quantity INTEGER NOT NULL, 
    FOREIGN KEY (orderId) REFERENCES orders(id),
    FOREIGN KEY (productId) REFERENCES products(id)
);
