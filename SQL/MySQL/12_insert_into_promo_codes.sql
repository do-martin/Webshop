START TRANSACTION;

INSERT INTO promo_codes (promo_code, sale, tries)
VALUES(
'webshop', 10, 10
);

SELECT * FROM new_webshop_project.promo_codes;

COMMIT;