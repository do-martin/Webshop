CREATE TABLE invoice_header(
id_invoice_number INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
order_date DATETIME NOT NULL,
shipping_company varchar(50) NOT NULL,
shipping_price DECIMAL(10,2) NOT NULL,
CONSTRAINT check_shipping_price_none_negative CHECK (shipping_price > 0.00),
subtotal DECIMAL(10,2) NOT NULL,
CONSTRAINT check_subtotal_none_negative CHECK (subtotal > 0.00),
total_amount DECIMAL(10,2) NOT NULL,
CONSTRAINT check_total_amount_none_negative CHECK (total_amount > 0.00),
id_promo_code int,
CONSTRAINT id_promo_code FOREIGN KEY (id_promo_code) REFERENCES promo_codes(id_promo_code),
used_points INT NOT NULL,
CONSTRAINT check_used_points_none_negative CHECK (used_points >= 0),
sales_promo_code DECIMAL(10,2) NOT NULL,
CONSTRAINT check_sales_promo_code_none_negative CHECK (sales_promo_code >= 0.00),
id_customer INT NOT NULL,
CONSTRAINT ih_id_customer FOREIGN KEY (id_customer) REFERENCES customers(id_customer)
);

SELECT * FROM invoice_header;