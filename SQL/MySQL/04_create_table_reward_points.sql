CREATE TABLE reward_points(
id_reward INT AUTO_INCREMENT PRIMARY KEY,
points INT NOT NULL,
CONSTRAINT check_points_none_negative CHECK (points >= 0),
id_customer INT NOT NULL UNIQUE,
CONSTRAINT fk_id_customer FOREIGN KEY (id_customer) REFERENCES customers(id_customer)
);