START TRANSACTION;

INSERT INTO products(
prod_name, 
price, 
item_inventory, 
path_img,
category,
gender
)
VALUES
('CUBA CADEN - Denim shorts', 50.99, 330, '/rsc/clothes/CUBA CADEN - Denim shorts pictureNumber.webp', 'shorts', 'm'),
('D-STAQ PKT SLIM - Slim fit jeans', 80.98, 120, '/rsc/clothes/D-STAQ PKT SLIM - Slim fit jeans pictureNumber.webp', 'trousers', 'm'),
('DULIVIO - Print T-shirt', 30.99, 530, '/rsc/clothes/DULIVIO - Print T-shirt pictureNumber.webp', 't-shirts', 'm'),
('JAMES - Tracksuit bottoms', 20, 250, '/rsc/clothes/JAMES - Tracksuit bottoms pictureNumber.webp', 'shorts', 'm'),
('Jeans Tapered Fit', 70, 1230, '/rsc/clothes/Jeans Tapered Fit pictureNumber.webp', 'trousers', 'm'),
('MONROE - Shorts', 50, 530, '/rsc/clothes/MONROE - Shorts pictureNumber.webp', 'shorts', 'm'),
('ORIGINAL TEE - Long sleeved top', 30, 120, '/rsc/clothes/ORIGINAL TEE - Long sleeved top pictureNumber.webp', 'pullover', 'm'),
('SLIM - Slim fit jeans', 80, 110, '/rsc/clothes/SLIM - Slim fit jeans pictureNumber.webp', 'trousers', 'm'),
('SLIM TAPER - Slim fit jeans', 50, 50, '/rsc/clothes/SLIM TAPER - Slim fit jeans pictureNumber.webp', 'trousers', 'm'),
('SLIM TAPER LO BALL - Jeans Tapered Fit', 55.99, 70, '/rsc/clothes/SLIM TAPER LO BALL - Jeans Tapered Fit pictureNumber.webp', 'trousers', 'm'),
('Trousers', 30, 120, '/rsc/clothes/Trousers pictureNumber.webp', 'trousers', 'm'),
('ROVIC ZÜP RELÄXED', 74.95, 200, '/rsc/clothes/ROVIC ZÜP RELÄXED pictureNumber.webp', 'shorts', 'm'),

('EMPORIO ARMANI TROUSERS - TROUSERS', 50.00, 200, '/rsc/clothes/EMPORIO ARMANI TROUSERS - TROUSERS pictureNumber.webp', 'trousers', 'w'),
('EMPORIO ARMANI SHIRT DRESS - BEACH ACCESSORY', 150.00, 200, '/rsc/clothes/EMPORIO ARMANI SHIRT DRESS - BEACH ACCESSORY pictureNumber.webp', 'dress', 'w'),
('EMPORIO ARMANI PANTALONI - TROUSERS', 150.00, 200, '/rsc/clothes/EMPORIO ARMANI PANTALONI - TROUSERS pictureNumber.webp', 'trousers', 'w'),
('EMPORIO ARMANI GIACCA - BLAZER', 350.00, 200, '/rsc/clothes/EMPORIO ARMANI GIACCA - BLAZER pictureNumber.webp', 'blazer', 'w'),
('EMPORIO ARMANI BUTTON-DOWN BLOUSE', 150.00, 200, '/rsc/clothes/EMPORIO ARMANI BUTTON-DOWN BLOUSE pictureNumber.webp', 'blouse', 'w'),
('EMPORIO ARMANI VESTITO - COCKTAIL DRESS', 250.00, 200, '/rsc/clothes/EMPORIO ARMANI VESTITO - COCKTAIL DRESS pictureNumber.webp', 'dress', 'w'),
('EMPORIO ARMANI TROUSERS', 150.00, 200, '/rsc/clothes/EMPORIO ARMANI TROUSERS pictureNumber.webp', 'trousers', 'w'),
('EMPORIO ARMANI BLUSA - BLOUSE', 50.00, 200, '/rsc/clothes/EMPORIO ARMANI BLUSA - BLOUSE pictureNumber.webp', 'blouse', 'w'),
('BOSS CLOMEA - Trenchcoat', 350.00, 200, '/rsc/clothes/BOSS CLOMEA - Trenchcoat pictureNumber.webp', 'coat', 'w'),
('Street One - Trousers', 150.00, 200, '/rsc/clothes/Street One - Trousers pictureNumber.webp', 'trousers', 'w'),
('Liu Jo Jeans Cargo trousers', 150.00, 200, '/rsc/clothes/Liu Jo Jeans Cargo trousers pictureNumber.webp', 'trousers', 'w'),
('ADOLFO DOMINGUEZ CRINKLE - Trousers', 150.00, 200, '/rsc/clothes/ADOLFO DOMINGUEZ CRINKLE - Trousers pictureNumber.webp', 'trousers', 'w');

SELECT * FROM products;

COMMIT;
