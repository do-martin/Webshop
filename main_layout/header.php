<!DOCTYPE html>
<html lang="de" style="height: 100%;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">

    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/index.css">
    <link href="../css/custom_forms.css" rel="stylesheet">
    <link href="../css/reset_password.css" rel="stylesheet">
    <link href="../css/products.css" rel="stylesheet">
    <link rel="stylesheet" href="main_layout/header.css">
    <link rel="stylesheet" href="../css/welcome.css">
    <link rel="stylesheet" href="../css/bootstrap-edit.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/media_query.css">
    <link rel="icon" href="../rsc/bitcoin-wallet.ico" type="image/x-icon">

    <?php if (isset($extraStyle)) {
        foreach ($extraStyle as $style) {
            echo '<link rel="stylesheet" href="' . $style . '">';
        }
    } ?>

    <script src="../data_download/jquery-3.7.1.min.js"></script>
    <script src="../js_functions/standardFunctions.js"></script>


    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
            border-radius: 0px !important;
        }
    </style>

</head>

<body style="height: 100%;" onload="pageLoad()">

    <script>
        function pageLoad(){
            console.log("Page loaded.");
        }
    </script>

    <header style="min-height: 10%;">

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand w-25" href="../index.php">
                    Styleshop
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) { ?>
                        <ul class="navbar-nav p-1">
                            <li class="nav-item dropdown">
                                <div class="dropdown h-100">
                                    <!-- <a class="btn btn-white dropdown-toggle my-account-dropdown h-100 d-flex justify-content-center align-items-center" type="button" id="dropdownProductButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="../products.php"> -->
                                    <a class="btn btn-white dropdown-toggle my-account-dropdown h-100 d-flex align-items-center" id="dropdownProductButton" href="../products.php">
                                        Products
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownProductButton">
                                        <a class="dropdown-item navbar-font-upper password-nav-link password-nav-link-text" href="../products.php?gender=m">male</a>
                                        <a class="dropdown-item navbar-font-upper password-nav-link password-nav-link-text" href="../products.php?gender=w">female</a>
                                    </div>
                                </div>
                            </li>

                        <?php } else { ?>
                            <ul class="navbar-nav p-1">
                                <li class="nav-item active d-flex align-items-center">
                                    <a style="text-transform: uppercase; letter-spacing: 3px; font-size:large;" class="nav-link" href="../products.php">
                                        Products
                                    </a>
                                </li>
                            </ul>
                            <ul class="navbar-nav p-1">
                                <li class="nav-item active d-flex align-items-center">
                                    <a style="text-transform: uppercase; letter-spacing: 3px; font-size:large;" class="nav-link" href="../products.php?gender=m">
                                        Male
                                    </a>
                                </li>
                            </ul>
                            <ul class="navbar-nav p-1">
                                <li class="nav-item active d-flex align-items-center">
                                    <a style="text-transform: uppercase; letter-spacing: 3px; font-size:large;" class="nav-link" href="../products.php?gender=w">
                                        Female
                                    </a>
                                </li>
                            </ul>
                        <?php } ?>


                        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) { ?>

                            <li class="nav-item d-flex align-items-center">
                                <a class="d-flex align-items-center nav-link mr-auto" style="text-transform: uppercase; letter-spacing: 3px; font-size:large;" href="../my_orders.php">
                                    Orders
                                </a>
                            </li>

                            <li class="nav-item dropdown">

                                <div class="dropdown h-100">
                                    <button class="btn btn-white dropdown-toggle my-account-dropdown h-100" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        My Account
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item navbar-font-upper my-data-nav-link my-data-nav-link-text" href="../my-data.php">My Data</a>
                                        <a class="dropdown-item navbar-font-upper password-nav-link password-nav-link-text" href="../create-new-address.php">Add new address</a>
                                        <a class="dropdown-item navbar-font-upper password-nav-link password-nav-link-text" href="../change-password.php">Password</a>
                                    </div>
                                </div>
                            </li>

                        <?php } ?>

                        <li class="nav-item active d-flex align-items-center">
                            <!-- d-flex align-items-center nav-link mr-auto" style="text-transform: uppercase; letter-spacing: 3px; font-size:large;" -->
                            <button style="text-transform: uppercase; letter-spacing: 3px; font-size:large; padding-top:8px !important; padding-bottom:8px !important;" class="nav-link p-1" onclick="scrollToId('legal-disclosure')">
                                Imprint
                            </button>
                        </li>

                        </ul>
                        <ul class="navbar-nav ms-auto">
                            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) { ?>
                                <li class="nav-item d-flex align-items-center">
                                    <a class="nav-link" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="currentColor" class="bi bi-handbag-fill" viewBox="0 0 16 16">
                                            <path d="M8 1a2 2 0 0 0-2 2v2H5V3a3 3 0 1 1 6 0v2h-1V3a2 2 0 0 0-2-2M5 5H3.36a1.5 1.5 0 0 0-1.483 1.277L.85 13.13A2.5 2.5 0 0 0 3.322 16h9.355a2.5 2.5 0 0 0 2.473-2.87l-1.028-6.853A1.5 1.5 0 0 0 12.64 5H11v1.5a.5.5 0 0 1-1 0V5H6v1.5a.5.5 0 0 1-1 0z" />
                                            <text style="letter-spacing: 1px;" id="cart-icon-value" x="5" y="14" font-size="6" fill="white"></text>
                                        </svg>
                                    </a>
                                </li>
                                <li class="nav-item d-flex align-items-center">
                                    <button class="nav-link d-flex" type="button" onclick="showLiveToast()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                        </svg>
                                        <text class="d-flex align-items-center" style="letter-spacing: 1px;" id="users-icon-value" x="5" y="14" font-size="6" fill="white">0</text>
                                    </button>
                                </li>
                                <li class="nav-item d-flex align-items-center">
                                    <!-- <a class="nav-link" href="../logout.php"> -->
                                    <button type="button" class="nav-link" onclick="redirectLogout()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="currentColor" class="bi bi-door-closed-fill" viewBox="0 0 16 16">
                                            <path d="M12 1a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2a1 1 0 0 1 1-1zm-2 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                        </svg>
                                        <!-- </a> -->
                                    </button>
                                </li>
                            <?php } ?>

                            <?php
                            if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"]))) { ?>
                                <li class="nav-item d-flex align-items-center p-1" style="padding-top:8px !important; padding-bottom:8px !important;">
                                    <a style="text-transform: uppercase; letter-spacing: 3px; font-size:large;" class="nav-link" href="../welcome.php">
                                        <i class="fas fa-user"></i>
                                        Login / Register
                                    </a>
                                </li>
                            <?php }
                            ?>
                        </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="modal fade" id="onlineUserModal" tabindex="-1" role="dialog" aria-labelledby="onlineUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="onlineUserModalLabel">Online Users</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Cart</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div id="cart">
            </div>
        </div>
        <div class="offcanvas-footer p-3">
            <div class="total-summary mt-3 d-flex w-100 justify-content-space-between pl-2 pr-2">
                <h5 class="w-50">Total amount</h5>
                <h5 class="w-50" style="display:flex; justify-content: flex-end;" id="products-total-amount"></h5>
            </div>
            <div class="total-summary d-flex w-100 justify-content-space-between pl-2 pr-2">
                <h6 class="w-50">Subtotal</h6>
                <h6 class="w-50" style="display:flex; justify-content: flex-end;" id="products-subtotal"></h6>
            </div>
            <div class="total-summary d-flex w-100 justify-content-space-between pl-2 pr-2">
                <h6 class="w-50">Discount</h6>
                <h6 class="w-50" style="display:flex; justify-content: flex-end;" id="products-discount"></h6>
            </div>
            <button onclick="redirectCheckoutPage()" class="btn mt-3 ml-2 mr-2 w-100" style="transition: background-color 1 ease-in-out; background-color:#212121; color: white;" id="btn-cart-check-out">CHECK OUT</button>

        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <svg class="rounded me-2" xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                </svg>
                <strong class="me-auto">Online Customers</strong>
                <small>couple seconds ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="userList">
                No users online.
            </div>
        </div>
    </div>

    <main style="min-height: 80%;">