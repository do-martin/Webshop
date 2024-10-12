CREATE TABLE products(
id_item_num INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
prod_name VARCHAR(150) NOT NULL,
price DECIMAL(10,2) NOT NULL, 
item_inventory INT NOT NULL DEFAULT 0,
CONSTRAINT check_inventory_none_negative CHECK(item_inventory >= 0),
path_img VARCHAR(255) NOT NULL,
category varchar(50) NOT NULL,
gender CHAR(1) NOT NULL DEFAULT 'd'
);

SELECT * FROM products;
