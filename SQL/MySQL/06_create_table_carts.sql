CREATE TABLE carts(
id_cart INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
id_customer INT NOT NULL,
id_item_num INT NOT NULL,
amount INT NOT NULL,
CONSTRAINT check_amount_only_positive CHECK (amount > 0),
CONSTRAINT fk_carts_id_customer FOREIGN KEY(id_customer) REFERENCES customers(id_customer),
CONSTRAINT fk_id_item_num FOREIGN KEY(id_item_num) REFERENCES products(id_item_num)
);

SELECT * FROM carts;
