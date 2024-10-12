CREATE TABLE two_factor_authentification(
id_customer INT NOT NULL PRIMARY KEY,
auth_key varchar(512) NOT NULL,
CONSTRAINT fk_customer_id
FOREIGN KEY (id_customer)
REFERENCES customers(id_customer)
);

SELECT * from two_factor_authentification;
