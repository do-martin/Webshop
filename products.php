<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!defined('MY_APP')) {
  define('MY_APP', true);
}

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/models/productModel.php";
require_once $path . "/php_functions/checkForFirstLogin.php";
require_once $path . "/php_functions/productFunctions.php";

$title = "Products";

$product_list = [];

if(isset($_GET['gender']) && $_GET['gender'] == 'm'){
  $product_list = getProductData("m");
} else if(isset($_GET['gender']) && $_GET['gender'] == 'w'){
  $product_list = getProductData("w");
} else {
  $product_list = getProductData();
}

?>

<?php include("main_layout/header.php"); ?>

<div class="row d-flex justify-content-center align-items-center" style="height: 75px; border-top: 2px solid black; border-bottom: 2px solid black; background-color:white;">

  <div class="col-3 h-100 justify-content-center align-items-center">
    <button class="btn h-100 justify-content-center align-items-center btn-show-filter products-header" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">Show Filters</button>
  </div>

  <div class="col-6 h-100 justify-content-center align-items-center">
    <h2 class="d-flex justify-content-center align-items-center h-100 products-headline" style="text-transform: none; padding-top:0px;">Products</h2>
  </div>

  <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Filter options</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="form-inline my-2 my-lg-0 d-flex">
        <input class="form-control mr-sm-2" onchange="filterProducts()" id="search-name" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-secondary my-2 my-sm-0" type="buton" onclick="filterProducts()">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
          </svg>
        </button>
      </div>
      <h5 class="mt-4">Category</h5>
      <hr>
      <div class="accordion" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
          <h2 class="accordion-header" id="panelsStayOpen-headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
              Tops
            </button>
          </h2>
          <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
            <div class="accordion-body">
              <div class="form-check m-3">
                <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="coat" id="filter-coat">
                <label class="form-check-label" for="filter-coat">
                  coat
                </label>
              </div>
              <div class="form-check m-3">
                <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="blazer" id="filter-blazer">
                <label class="form-check-label" for="filter-blazer">
                  blazer
                </label>
              </div>
              <?php if (!isset($_GET['gender']) || ($_GET['gender'] != 'm')) { ?>
                <div class="form-check m-3">
                  <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="dress" id="filter-dress">
                  <label class="form-check-label" for="filter-dress">
                    dress
                  </label>
                </div>
              <?php } ?>
              <div class="form-check m-3">
                <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="pullover" id="filter-pullover">
                <label class="form-check-label" for="filter-pullover">
                  pullover
                </label>
              </div>
              <?php if (!isset($_GET['gender']) || ($_GET['gender'] != 'm')) { ?>
                <div class="form-check m-3">
                  <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="blouse" id="filter-blouse">
                  <label class="form-check-label" for="filter-blouse">
                    blouse
                  </label>
                </div>
              <?php } ?>
              <div class="form-check m-3">
                <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="t-shirts" id="filter-t-shirts">
                <label class="form-check-label" for="filter-t-shirts">
                  t-shirts
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
              Bottoms
            </button>
          </h2>
          <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
            <div class="accordion-body">
              <div class="form-check m-3">
                <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="trousers" id="filter-trousers">
                <label class="form-check-label" for="filter-trousers">
                  trousers
                </label>
              </div>
              <div class="form-check m-3">
                <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="shorts" id="filter-shorts">
                <label class="form-check-label" for="filter-shorts">
                  shorts
                </label>
              </div>
            </div>
          </div>
        </div>
        <?php if (!(isset($_GET['gender']) && ($_GET['gender'] == 'm' || $_GET['gender'] == 'w'))) { ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                Gender
              </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
              <div class="accordion-body">
                <div class="form-check m-3">
                  <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="m" id="filter-male">
                  <label class="form-check-label" for="filter-male">
                    male
                  </label>
                </div>
                <div class="form-check m-3">
                  <input onchange="filterProducts()" class="form-check-input filter-checkbox" type="checkbox" checked="true" value="w" id="filter-female">
                  <label class="form-check-label" for="filter-female">
                    female
                  </label>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
      <h5 class="mt-4">Price up to</h5>
      <hr>
      <div class="price-range">
        <input onchange="filterProducts()" type="range" class="form-range mb-3" id="priceRange">
        <label for="priceRange" class="form-label">
          <span class="price-range__min">0 €</span>
          <strong class="price-range__mid"><span id="select-price-range"></span> €</strong>
          <span class="price-range__max">500 €</span>
        </label>
      </div>
      <button type="button" class="mt-4 w-100 btn btn-secondary" onclick="resetFilterOptions()">RESET FILTER OPTIONS</button>
    </div>
  </div>

  <div class="col-3 h-100 d-flex justify-content-end align-items-center">
    <div class="btn-group h-100">
      <button type="button" class="btn btn-sort-by dropdown-toggle products-header" data-bs-toggle="dropdown" aria-expanded="false">
        Sort by
      </button>
      <ul class="dropdown-menu dropdown-menu-end border" style="border-radius: 0px;">
        <li><button class="dropdown-item btn-sort-products" type="button" onclick="sortProducts('alphabetically-a-z')">Alphabetically from A to Z</button></li>
        <li><button class="dropdown-item btn-sort-products" type="button" onclick="sortProducts('alphabetically-z-a')">Alphabetically from Z to A</button></li>
        <li><button class="dropdown-item btn-sort-products" type="button" onclick="sortProducts('price-low-to-high')">Price, low to high</button></li>
        <li><button class="dropdown-item btn-sort-products" type="button" onclick="sortProducts('price-high-to-low')">Price, high to low</button></li>
      </ul>
    </div>
  </div>

</div>

<div class="content" id="content" style="padding-left: 0px; padding-right: 0px;">
  <div class="row justify-content-center all-products" id="all-products">
    <?php foreach ($product_list as $product) { ?>

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
                  <button id="btn-insert-item-<?php echo $product->getItemNumber(); ?>-into-cart" class="btn btn-light w-25 btn-add-to-cookie d-flex justify-content-center align-items-center" onclick="insertFromProductPageIntoCart(
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

<?php include("main_layout/footer.php"); ?>