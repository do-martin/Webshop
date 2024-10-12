BEGIN TRANSACTION;

INSERT INTO promo_codes (promo_code, sale, tries)
VALUES(
'webshop', 10, 10
);

SELECT * FROM promo_codes;

COMMIT;