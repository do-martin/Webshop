<?php
// if ((empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
//     && !defined('MY_APP')
//     && (empty($_SERVER['HTTP_CONTENT_TYPE']) || strtolower($_SERVER['HTTP_CONTENT_TYPE']) !== 'application/json')
// ) {
//     header("location: ../index.php");
// }else{
//     if(!defined('MY_APP')){
//         define('MY_APP', true);
//     }
// }

$test = session_status();
if (session_status() === PHP_SESSION_NONE) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/config/config.php";
require_once $path . "/models/cartModel.php";
require_once $path . "/php_functions/userFunctions.php";
require_once $path . "/php_functions/cartFunctions.php";
require_once $path . "/php_functions/rewardsFunctions.php";

$cart_items = array();
$totalAmount = 0;
$totalAmountBasic = 0;
$subtotal = 0;
$subtotalBasic = 0;
$rewardPoints = 0;

$prod_name = $id_item_num = $amount = $price = "";
$id_customer = $first_name = $last_name = $street = $postal_code = $location = $country = $gender = "";
$username = $_SESSION['username'];

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
    $cart_items = getCartItemsByUsername($username);
    if($cart_items == null){
        header("location: /products.php");
    }

    foreach ($cart_items as $cart) {
        $totalAmount += $cart->getTotalAmountAfterSale();
        $totalAmountBasic += $cart->getTotalAmountAfterSale();
        $subtotal += $cart->getTotalAmount();
        $subtotalBasic += $cart->getTotalAmount();
    }

    $userMainData = getUserMainData($username);
    $id_customer = $userMainData["id_customer"];
    $username = $userMainData["username"];
    $first_name = $userMainData["first_name"];
    $last_name = $userMainData["last_name"];
    $street = $userMainData["street"];
    $postal_code = $userMainData["postal_code"];
    $location = $userMainData["location"];
    $country = $userMainData["country"];
    $gender = $userMainData["gender"];

    $rewardPoints = getRewardPoints($username);
}
