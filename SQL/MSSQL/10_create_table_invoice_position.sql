CREATE TABLE invoice_position(
id_invoice_position INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
id_invoice_number INT NOT NULL,
id_item_num INT NOT NULL,
amount INT NOT NULL,
CONSTRAINT chck_amount_none_negative CHECK (amount > 0),
price DECIMAL(10,2) NOT NULL,
CONSTRAINT check_price_only_positive CHECK (price > 0.00),
CONSTRAINT fk_ip_invoice_number FOREIGN KEY (id_invoice_number) REFERENCES invoice_header(id_invoice_number),
CONSTRAINT fk_ip_item_num FOREIGN KEY(id_item_num) REFERENCES products(id_item_num)
);

SELECT * FROM invoice_position;