CREATE TABLE promo_codes(
id_promo_code INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
promo_code varchar(300) NOT NULL UNIQUE,
sale INT NOT NULL,
CONSTRAINT check_sale_none_negative CHECK (sale >= 0),
tries int NOT NULL,
CONSTRAINT check_tries_none_negative CHECK (tries >= 0)
);

SELECT * FROM promo_codes;
