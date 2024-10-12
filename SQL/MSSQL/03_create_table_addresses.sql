CREATE TABLE addresses(
id_address INT PRIMARY KEY IDENTITY(1,1) NOT NULL,
first_name varchar(50) NOT NULL,
last_name varchar(50) NOT NULL,
gender CHAR(1) NOT NULL,
street VARCHAR(50) NOT NULL,
zip INT NOT NULL,
location varchar(50) NOT NULL,
country VARCHAR(50) NOT NULL,
main_address BIT NOT NULL DEFAULT 0,
id_customer INT NOT NULL,
CONSTRAINT customer_ibfk_1
FOREIGN KEY (id_customer)
REFERENCES customers(id_customer)
);

SELECT * FROM addresses;