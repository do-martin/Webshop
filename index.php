<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
    header("location: /welcome.php");
    exit;
}

if (!defined('MY_APP')) {
    define('MY_APP', true);
}

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/config/config.php";
require_once $path . "/models/productModel.php";
require_once $path . "/php_functions/productFunctions.php";

$title = "Home";

$product_list_m = getProductData("m", 4);
$product_list_f = getProductData("w", 4);

$extraScript = "<script src='../data_download/old_bootstrap/bootstrap.bundle.min.js'></script>";

?>

<?php include("main_layout/header.php"); ?>

<div id="carouselHome" class="carousel slide bg-black carousel-height" data-ride="carousel">
    <div class="carousel-inner carousel-height">
        <div class="carousel-item active carousel-height">
            <img class="d-block w-100" src="rsc/welcome/yourMainImage2.webp" alt="First slide">
            <div class="carousel-caption d-sm-block">
                <h3>Welcome to Styleshop</h3>
            </div>
        </div>
        <div class="carousel-item carousel-height">
            <img class="d-block w-100" src="rsc/welcome/yourMainImage3.webp" alt="Second slide">
            <div class="carousel-caption d-sm-block">
                <h5>Shop, earn points, and save money</h5>
            </div>
        </div>
        <div class="carousel-item carousel-height">
            <img class="d-block w-100" src="rsc/welcome/yourMainImage.webp" alt="Third slide">
            <div class="carousel-caption d-sm-block">
                <h5>New fashion</h5>
                <p>The latest from new here with us.</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselHome" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselHome" role="button" data-slide="next">
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