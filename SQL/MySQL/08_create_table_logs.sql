CREATE TABLE logs(
id_logs INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
activity BOOLEAN NOT NULL,
last_login DATETIME NOT NULL,
last_screen_res VARCHAR(20) NOT NULL,
last_op_system VARCHAR(40) NOT NULL,
id_customer INT NOT NULL UNIQUE,
CONSTRAINT fk_logs_id_customer FOREIGN KEY (id_customer) REFERENCES customers(id_customer)
);

SELECT * FROM logs;