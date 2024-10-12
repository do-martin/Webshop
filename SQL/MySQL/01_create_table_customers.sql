CREATE TABLE customers(
id_customer INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
username varchar(100) NOT NULL UNIQUE,
pw varchar(300) NOT NULL,
first_name varchar(50) NOT NULL,
last_name varchar(50) NOT NULL,
street varchar(50) NOT NULL,
postal_code varchar(50) NOT NULL,
location varchar(50) NOT NULL,
country varchar(50) NOT NULL,
gender char(1) NOT NULL DEFAULT 'd',
two_factor_auth BOOLEAN NOT NULL DEFAULT FALSE
);

SELECT * FROM customers