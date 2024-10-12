<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false || !isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("location: /login.php");
    exit;
}

if (!defined('MY_APP')) {
    define('MY_APP', true);
}

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/config/config.php";
require_once $path . "/php_functions/checkForFirstLogin.php";
require_once $path . "/php_functions/rewardsFunctions.php";
require_once $path . "/models/productModel.php";
require_once $path . "/php_functions/productFunctions.php";

$title = "Welcome";
$points = 0;
$user_gender = $last_name = $last_login = "";
$product_list_m = $product_list_f = [];
$extraScript = "<script src='../data_download/old_bootstrap/bootstrap.bundle.min.js'></script>";

if (!empty($_SESSION["username"]) && !empty($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {

    if (!isset($_GET["username"]) && !isset($_GET["last_name"]) && !isset($_GET["last_login"]) && !isset($_GET["gender"]) && !isset($_GET["user_current_os"]) && !isset($_GET["user_current_screen_res"])) {
        $sql_get_user_data = "SELECT
        c.id_customer,
        c.last_name,
        c.gender,
        l.last_login
        FROM customers AS c
        JOIN logs AS l ON c.id_customer = l.id_customer
        WHERE c.username = ?";

        if ($stmt = $conn->prepare($sql_get_user_data)) {
            $param_username = trim($_SESSION["username"]);
            $stmt->bindValue(1, $param_username, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($results) > 0) {
                    $id_customer_db = $results[0]['id_customer'];
                    $last_name_db = $results[0]['last_name'];
                    $gender_db = $results[0]['gender'];
                    $last_login_db = $results[0]['last_login'];
                    
                    $_SESSION["loggedin"] = true;
                    $_SESSION["username"] = $param_username;
                    $last_name = $last_name_db;
                    $last_login = $last_login_db;
                    $user_gender = $gender_db;
                } else {
                    echo "No user found with the provided username.";
                }
            } else {
                echo "Oops! Something went wrong: " . $stmt->error;
            }
        } else {
            echo "Oops! Something went wrong: " . $conn->error;
        }
    } else {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $_GET["username"];
        $last_name = $_GET["last_name"];
        $last_login = $_GET["last_login"];
        $user_gender = $_GET["gender"];
    }

    $points = getRewardPoints($_SESSION["username"]);
    $product_list_m = getProductData("m", 4);
    $product_list_f = getProductData("w", 4);
}
?>

<?php include("main_layout/header.php"); ?>

<div id="carouselExampleControls" class="carousel slide bg-black carousel-height" data-ride="carousel">
    <div class="carousel-inner carousel-height">
        <div class="carousel-item active carousel-height">
            <img class="d-block w-100" src="rsc/welcome/1711362877470-homepagefashiononedesktopjpg_3240x5760.webp" alt="First slide">
            <div class="carousel-caption d-sm-block">
                <h3>Welcome <b><?php if ($user_gender == "m") {
                                    echo htmlspecialchars("Mr.");
                                } else if ($user_gender == 'd') {
                                    echo htmlspecialchars("Mr./Mrs.");
                                } else {
                                    echo htmlspecialchars("Mrs.");
                                } ?></b> <b><?php echo htmlspecialchars($last_name); ?></b></h3>
                <p>Your last online activity was on <?php echo htmlspecialchars($last_login); ?></p>
            </div>
        </div>
        <div class="carousel-item carousel-height">
            <img class="d-block w-100" src="rsc/welcome/1691050806675-hpcorpoone2880x1260v21jpg_1260x2880.webp" alt="Second slide">
            <div class="carousel-caption d-sm-block">
                <h5>Shop, earn points, and save money</h5>
                <p>You have <b><?php echo $points; ?></b> reward points.</p>
            </div>
        </div>
        <div class="carousel-item carousel-height">
            <img class="d-block w-100" src="rsc/welcome/1710432541893-314ravdesktopjpg_1260x2880.webp" alt="Third slide">
            <div class="carousel-caption d-sm-block">
                <h5>New fashion</h5>
                <p>The latest from new here with us.</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-12 text-center font-weight-bold">
            <p style="font-weight: bold;">Discover a world of fashion where elegance and style are the core. Our exclusive collection ranges from chic dresses to stylish blazers, trendy tops, cozy pullovers, and much more. Whether you are looking for a casual look or something more formal, we have it all.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center font-weight-bold">
            <p style="font-weight: bold;">Visit us in Reutlingen and experience beauty and relaxation in our Italian espresso bar. Book your appointment now and indulge in the special experience we offer.</p>
        </div>
    </div>
</div>

<div class="container d-flex mt-5 mb-5">
    <div class="row">
        <a href="products.php?gender=w" class="text-decoration-none" style="color:black;">
            <h2 class="text-center font-weight-bold header-for-women-for-men" style="font-weight: bold;">For Women</h2>
        </a>
        <p class="text-center">Experience pure elegance and treat yourself to our exclusive women's fashion collection.</p>
        <div class="row justify-content-center all-products" id="all-products-f">
            <?php foreach ($product_list_f as $product) { ?>

                <div style="transition: transform 0.5s ease;" class="col-3 product p-0 text-decoration-none" data-content="one-product=<?php echo str_replace(' ', '', $product->getProductName()); ?> price=<?php echo $product->getPrice(); ?> category=<?php echo $product->getCategory(); ?> gender=<?php echo $product->getGender(); ?>" style="color:#000">
                    <div id="<?php echo $product->getItemNumber(); ?>" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <a class="carousel-item active">
                                <img src="<?php echo $product->getPathImg(1); ?>" class="d-block w-100" alt="<?php echo $product->getProductName(); ?>">
                            </a>
                            <div class="carousel-item">
                                <img src="<?php echo $product->getPathImg(2); ?>" class="d-block w-100" alt="<?php echo $product->getProductName(); ?>">
                            </div>
                            <div class="carousel-item">
                                <img src="<?php echo $product->getPathImg(3); ?>" class="d-block w-100" alt="<?php echo $product->getProductName(); ?>">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $product->getItemNumber(); ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $product->getItemNumber(); ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div style="border-top: 1px solid #000;" class="pb-4">
                        <div class="d-flex justify-content-center">
                            <div class="pt-3">
                                <div class="d-flex justify-content-center" style="font-size:smaller;">Item number: <?php echo $product->getItemNumber(); ?></div>
                                <div class="d-flex justify-content-center all-product-names text-center pl-5 pr-5" style="font-size:14px;"><?php echo $product->getProductName(); ?></div>
                                <div class="d-flex justify-content-center" style="font-size:smaller;">inventory: <?php echo $product->getItemInventory(); ?></div>
                                <div class="d-flex justify-content-center pb-3" style="font-size:14px;">price: <?php echo $product->getPrice(); ?> €</div>

                                <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) { ?>
                                    <div class="d-flex justify-content-center">

                                        <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center" type="button" onclick="plusMinusActionhandler(-1, <?php echo $product->getItemNumber(); ?>, <?php echo $product->getItemInventory(); ?>)">-</button>
                                        <input type="number" class="form-control text-center" id="minus-plus-to-cart-<?php echo $product->getItemNumber(); ?>" min="0" max="<?php echo $product->getItemInventory(); ?>" placeholder="max <?php echo $product->getItemInventory(); ?>">
                                        <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center" type="button" onclick="plusMinusActionhandler(1, <?php echo $product->getItemNumber(); ?>, <?php echo $product->getItemInventory(); ?>)">+</button>
                                        <button class="btn btn-light w-25 btn-add-to-cookie d-flex justify-content-center align-items-center" onclick="insertFromProductPageIntoCart(
                    JSON.stringify({                               
                    'item_number': '<?php echo htmlspecialchars($product->getItemNumber()); ?>',
                    'product_name': '<?php echo htmlspecialchars($product->getProductName()); ?>',               
                    'price': '<?php echo htmlspecialchars($product->getPrice()); ?>',
                    'path': '<?php echo htmlspecialchars($product->getPathImg(1)); ?>',
                    'category': '<?php echo htmlspecialchars($product->getCategory()); ?>',
                    'item_inventory': '<?php echo htmlspecialchars($product->getItemInventory()); ?>',
                    'amount': parseInt(document.getElementById('minus-plus-to-cart-<?php echo $product->getItemNumber(); ?>').value) || 0}))">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-handbag-fill" viewBox="0 0 16 16">
                                                <path d="M8 1a2 2 0 0 0-2 2v2H5V3a3 3 0 1 1 6 0v2h-1V3a2 2 0 0 0-2-2M5 5H3.36a1.5 1.5 0 0 0-1.483 1.277L.85 13.13A2.5 2.5 0 0 0 3.322 16h9.355a2.5 2.5 0 0 0 2.473-2.87l-1.028-6.853A1.5 1.5 0 0 0 12.64 5H11v1.5a.5.5 0 0 1-1 0V5H6v1.5a.5.5 0 0 1-1 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="text-danger d-flex justify-content-center p-1" style="font-size: small;" id="error-message-<?php echo $product->getItemNumber(); ?>"></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <a href="products.php?gender=m" class="text-decoration-none" style="color:black;">
            <h2 class="text-center font-weight-bold mt-5 header-for-women-for-men" style="font-weight: bold;">For Men</h2>
        </a>
        <p class="text-center">Discover style and class with our exclusive men's fashion collection.</p>
        <div class="row justify-content-center all-products" id="all-products-m">
            <?php foreach ($product_list_m as $product) { ?>

                <div style="transition: transform 0.5s ease;" class="col-3 product p-0 text-decoration-none" data-content="one-product=<?php echo str_replace(' ', '', $product->getProductName()); ?> price=<?php echo $product->getPrice(); ?> category=<?php echo $product->getCategory(); ?> gender=<?php echo $product->getGender(); ?>" style="color:#000">
                    <div id="<?php echo $product->getItemNumber(); ?>" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <a class="carousel-item active">
                                <img src="<?php echo $product->getPathImg(1); ?>" class="d-block w-100" alt="<?php echo $product->getProductName(); ?>">
                            </a>
                            <div class="carousel-item">
                                <img src="<?php echo $product->getPathImg(2); ?>" class="d-block w-100" alt="<?php echo $product->getProductName(); ?>">
                            </div>
                            <div class="carousel-item">
                                <img src="<?php echo $product->getPathImg(3); ?>" class="d-block w-100" alt="<?php echo $product->getProductName(); ?>">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $product->getItemNumber(); ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $product->getItemNumber(); ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div style="border-top: 1px solid #000;" class="pb-4">
                        <div class="d-flex justify-content-center">
                            <div class="pt-3">
                                <div class="d-flex justify-content-center" style="font-size:smaller;">Item number: <?php echo $product->getItemNumber(); ?></div>
                                <div class="d-flex justify-content-center all-product-names text-center pl-5 pr-5" style="font-size:14px;"><?php echo $product->getProductName(); ?></div>
                                <div class="d-flex justify-content-center" style="font-size:smaller;">inventory: <?php echo $product->getItemInventory(); ?></div>
                                <div class="d-flex justify-content-center pb-3" style="font-size:14px;">price: <?php echo $product->getPrice(); ?> €</div>

                                <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) { ?>
                                    <div class="d-flex justify-content-center">

                                        <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center" type="button" onclick="plusMinusActionhandler(-1, <?php echo $product->getItemNumber(); ?>, <?php echo $product->getItemInventory(); ?>)">-</button>
                                        <input type="number" class="form-control text-center" id="minus-plus-to-cart-<?php echo $product->getItemNumber(); ?>" min="0" max="<?php echo $product->getItemInventory(); ?>" placeholder="max <?php echo $product->getItemInventory(); ?>">
                                        <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center" type="button" onclick="plusMinusActionhandler(1, <?php echo $product->getItemNumber(); ?>, <?php echo $product->getItemInventory(); ?>)">+</button>
                                        <button class="btn btn-light w-25 btn-add-to-cookie d-flex justify-content-center align-items-center" onclick="insertFromProductPageIntoCart(
                    JSON.stringify({                               
                    'item_number': '<?php echo htmlspecialchars($product->getItemNumber()); ?>',
                    'product_name': '<?php echo htmlspecialchars($product->getProductName()); ?>',               
                    'price': '<?php echo htmlspecialchars($product->getPrice()); ?>',
                    'path': '<?php echo htmlspecialchars($product->getPathImg(1)); ?>',
                    'category': '<?php echo htmlspecialchars($product->getCategory()); ?>',
                    'item_inventory': '<?php echo htmlspecialchars($product->getItemInventory()); ?>',
                    'amount': parseInt(document.getElementById('minus-plus-to-cart-<?php echo $product->getItemNumber(); ?>').value) || 0}))">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-handbag-fill" viewBox="0 0 16 16">
                                                <path d="M8 1a2 2 0 0 0-2 2v2H5V3a3 3 0 1 1 6 0v2h-1V3a2 2 0 0 0-2-2M5 5H3.36a1.5 1.5 0 0 0-1.483 1.277L.85 13.13A2.5 2.5 0 0 0 3.322 16h9.355a2.5 2.5 0 0 0 2.473-2.87l-1.028-6.853A1.5 1.5 0 0 0 12.64 5H11v1.5a.5.5 0 0 1-1 0V5H6v1.5a.5.5 0 0 1-1 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="text-danger d-flex justify-content-center p-1" style="font-size: small;" id="error-message-<?php echo $product->getItemNumber(); ?>"></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container d-flex mt-5 mb-5">
    <div class="col-12 text-center font-weight-bold mt-5" style="font-weight: bold;">
        <p>Embark on a journey through the latest fashion trends and timeless elegance showcased in our collection. Immerse yourself in a world of sartorial splendor and style diversity, where every piece tells a story of sophistication and allure. Whether you're seeking chic essentials or statement pieces, our exclusive selection offers something for every taste and occasion. Indulge in the pleasure of discovering the finest craftsmanship and the most coveted designs. Explore our curated range today and let your personal style shine!</p>
        <a href="products.php" class="btn btn-secondary" style="font-weight:bold; text-transform: uppercase; letter-spacing: 3px;">All Products</a>
    </div>
</div>

<?php include("main_layout/footer.php"); ?>