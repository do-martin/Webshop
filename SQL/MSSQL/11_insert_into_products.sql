BEGIN TRANSACTION;

INSERT INTO products(
prod_name, 
price, 
item_inventory, 
path_img,
category,
gender
)
VALUES
('name of your product', 50.99, 330, '/rsc/path to img.webp', 'shorts', 'm'),

('name of your product', 50.99, 330, '/rsc/path to img.webp', 'shorts', 'w'),

SELECT * FROM products;

COMMIT;
