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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/models/cartModel.php";
require_once $path . "/php_functions/phpMailer.php";
// require_once $path . "/php_functions/sqlSelects.php";
require_once $path . "/php_functions/cartFunctions.php";
require_once $path . "/php_functions/rewardsFunctions.php";

function insertIntoInvoiceHeaderByUsername($cart_items, $totalAmountAfterSale, $shipping_company, $shipping_price, $username, $reward_points, $subtotal, $sales_promo_code)
{
    global $conn;
    $param_shipping_company = $param_username = "";
    $param_shipping_price = $param_total_amount = $param_subtotal = $param_sales_promo_code = 0.00;
    $last_id = "";
    $param_used_points = 0;

    $sql_create_invoice_header = "INSERT INTO invoice_header
    (order_date, shipping_company, shipping_price, total_amount, id_customer, used_points, subtotal, sales_promo_code)
    VALUES(
    GETDATE(), :shipping_company, :shipping_price, :total_amount, 
    (SELECT id_customer FROM customers WHERE username = :customer),
    :used_points, :subtotal, :sales_promo_code)";

    if ($stmt_select = $conn->prepare($sql_create_invoice_header)) {
        $stmt_select->bindParam(':shipping_company', $param_shipping_company, PDO::PARAM_STR);
        $stmt_select->bindParam(':shipping_price', $param_shipping_price, PDO::PARAM_STR);
        $stmt_select->bindParam(':total_amount', $param_total_amount, PDO::PARAM_STR);
        $stmt_select->bindParam(':customer', $param_username, PDO::PARAM_STR);
        $stmt_select->bindParam(':used_points', $param_used_points, PDO::PARAM_INT);
        $stmt_select->bindParam(':subtotal', $param_subtotal, PDO::PARAM_STR);
        $stmt_select->bindParam(':sales_promo_code', $param_sales_promo_code, PDO::PARAM_STR);

        $param_shipping_company = $shipping_company;
        $param_shipping_price = round(floatval($shipping_price), 2);
        $param_username = $username;
        $param_used_points = ($reward_points - ($reward_points % 100));
        $param_subtotal = $subtotal;
        $param_subtotal += $param_shipping_price;
        $param_sales_promo_code = $sales_promo_code;

        $param_total_amount += $totalAmountAfterSale;
        $param_total_amount += $param_shipping_price;
        $param_total_amount -= $param_sales_promo_code;

        if ($stmt_select->execute()) {
            $last_id = $conn->lastInsertId();
            return ["success" => true, "last_id" => $last_id, "total_amount" => $param_total_amount];
        } else {
            $errorInfo = $stmt_select->errorInfo();
            // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
            throw new Exception("Error: " . $errorInfo);
        }
    } else {
        $errorInfo = $stmt_select->errorInfo();
        // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
        throw new Exception("Error: " . $errorInfo);
    }
    return ["success" => false];
}

function insertIntoInvoicePosition($cart_items, $last_id)
{
    global $conn;
    $param_id_item_num = $param_amount = $param_price = "";

    $sql_create_invoice_position = "INSERT INTO invoice_position(id_invoice_number, id_item_num, amount, price)
                                                VALUES(" . $last_id . ", :id_item_num, :amount, :price)";


    if ($stmt_insert = $conn->prepare($sql_create_invoice_position)) {
        $stmt_insert->bindParam(':id_item_num', $param_id_item_num, PDO::PARAM_INT);
        $stmt_insert->bindParam(':amount', $param_amount, PDO::PARAM_INT);
        $stmt_insert->bindParam(':price', $param_price, PDO::PARAM_STR);

        foreach ($cart_items as $cart_item) {
            $param_id_item_num = $cart_item->getItemNumber();
            $param_amount = $cart_item->getAmount();
            $param_price = $cart_item->getPrice();

            if ($stmt_insert->execute()) {
                $sql_reduce_item_inventory = "UPDATE products SET item_inventory = item_inventory - :amount WHERE id_item_num = :id_item_num";
                if ($stmt_update = $conn->prepare($sql_reduce_item_inventory)) {
                    $stmt_update->bindParam(':amount', $param_amount, PDO::PARAM_INT);
                    $stmt_update->bindParam(':id_item_num', $param_id_item_num, PDO::PARAM_INT);

                    $param_amount = $cart_item->getAmount();
                    $param_id_item_num = $cart_item->getItemNumber();
                    if ($stmt_update->execute()) {
                        if ($cart_items[count($cart_items) - 1] == $cart_item) {
                            return ["success" => true];
                        }
                    } else {
                        $errorInfo = $stmt_update->errorInfo();
                        // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
                        throw new Exception("Error: " . $errorInfo);
                    }
                } else {
                    $errorInfo = $stmt_update->errorInfo();
                    // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
                    throw new Exception("Error: " . $errorInfo);
                }
            } else {
                $errorInfo = $stmt_insert->errorInfo();
                // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
                throw new Exception("Error: " . $errorInfo);
            }
        }
    } else {
        $errorInfo = $stmt_insert->errorInfo();
        // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
        throw new Exception("Error: " . $errorInfo);
    }
    return ["success" => false];
}

function decreasePromoCodes($promo_code)
{
    global $conn;
    $param_promo_code = "";
    $promo_code = explode(",", $promo_code);
    $promo_code = array_unique($promo_code);
    foreach ($promo_code as $code) {
        $sql_decrease_promo_code = "UPDATE promo_codes SET tries = tries - 1 WHERE promo_code = :promo_code";
        if ($stmt_update = $conn->prepare($sql_decrease_promo_code)) {
            $stmt_update->bindParam(':promo_code', $param_promo_code, PDO::PARAM_STR);
            $param_promo_code = $code;
            if ($stmt_update->execute()) {
                if ($promo_code[count($promo_code) - 1] == $code) {
                    return true;
                }
            } else {
                $errorInfo = $stmt_update->errorInfo();
                // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
                throw new Exception("Error: " . $errorInfo);
            }
        } else {
            $errorInfo = $stmt_update->errorInfo();
            // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
            throw new Exception("Error: " . $errorInfo);
        }
    }
}

function getSaleForPromoCodes($promo_code)
{
    global $conn;
    $param_promo_code = "";
    $total_sales = 0.00;
    $promo_code = explode(",", $promo_code);
    $inputCoded = "";
    for ($i = 0; $i < count($promo_code); $i++) {
        $inputCoded .= "'" . $promo_code[$i] . "'";
        if ($i < count($promo_code) - 1) {
            $inputCoded .= " OR promo_code = ";
        }
    }

    $sql_get_sale = "SELECT SUM(sale) AS total_sales FROM promo_codes WHERE promo_code = " . $inputCoded  . ";";

    if ($stmt_get_sale = $conn->prepare($sql_get_sale)) {

        if ($stmt_get_sale->execute()) {
            $results = $stmt_get_sale->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
                return $results[0]["total_sales"];
            } else {
                return 0.00;
            }
        } else {
            $errorInfo = $stmt_get_sale->errorInfo();
            // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
            throw new Exception("Error: " . $errorInfo);
        }
    } else {
        $errorInfo = $stmt_get_sale->errorInfo();
        // echo json_encode(['error' => 'Oops! Something went wrong. Please try again later.', 'details' => $errorInfo]);
        throw new Exception("Error: " . $errorInfo);
    }
    return 0.00;
}

if (isset($_POST["delivery"]) && isset($_POST["delivery-value-company"]) && isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["address"]) && isset($_POST["country"]) && isset($_POST["state"]) && isset($_POST["zip"])) {
    $username = $_SESSION["username"];
    $shipping_price = $_POST["delivery"];
    $shipping_company = $_POST["delivery-value-company"];
    $first_name = $_POST["firstName"];
    $last_name = $_POST["lastName"];
    $address = $_POST["address"];
    $country = $_POST["country"];
    $state = $_POST["state"];
    $zip = $_POST["zip"];
    $use_reward_points = $_POST["use-points-value"];
    $coupon_code = $_POST["use-promo-code-value"];
    $cart_items = array();
    $use_points = $_POST["use-points-value"];

    $conn->beginTransaction();

    try {
        $cart_items = getCartItemsByUsername($username);

        if ($cart_items == null) {
            echo json_encode(array('error' => 'No items in cart.'));
            return;
        }

        foreach ($cart_items as $cart_item) {
            if ($cart_item->getAmount() > $cart_item->getItemInventory()) {
                echo json_encode(array('error' => 'Not enough items in stock.'));
                return;
            }
        }

        $salesForPromoCodes = getSaleForPromoCodes($coupon_code);
        if ($salesForPromoCodes == null) {
            $salesForPromoCodes = 0.00;
        }
        if (!decreasePromoCodes($coupon_code)) {
            echo json_encode(array('error' => 'Promo code not valid.'));
        }
        $successfully_created_invoice = false;
        if (count($cart_items) > 0) {
            $totalAmountAfterSale = 0.00;
            $subtotal = 0.00;
            foreach ($cart_items as $cart_item) {
                $totalAmountAfterSale += $cart_item->getTotalAmountAfterSale();
                $subtotal += $cart_item->getTotalAmount();
            }

            $reward_points_tupel = array();
            $reward_points = 0;
            $modulo = 0;
            $restAfterModuloSubtraction = 0;
            $sale = 0.00;

            if ($use_points == true) {
                $reward_points_tupel = getCustomerRewardPoints($username);
                $reward_points = $reward_points_tupel["reward_points"];
                $modulo = $reward_points_tupel["modulo"];
                $restAfterModuloSubtraction = $reward_points - $modulo;
                $sale = $restAfterModuloSubtraction / 100 * 0.1;
                $totalAmountAfterSale -= $sale;
            }

            if ($use_points == true) {
                $result = insertIntoInvoiceHeaderByUsername($cart_items, $totalAmountAfterSale, $shipping_company, $shipping_price, $username, $reward_points, $subtotal, $salesForPromoCodes);
            } else {
                $result = insertIntoInvoiceHeaderByUsername($cart_items, $totalAmountAfterSale, $shipping_company, $shipping_price, $username, 0, $subtotal, $salesForPromoCodes);
            }

            if ($result["success"]) {
                $last_id = $result["last_id"];

                if (insertIntoInvoicePosition($cart_items, $last_id)["success"]) {
                    $successfully_get_points = updateRewardPointsSQL($username, 25);
                    if (deleteCartItemsByUsername(($username))["success"]) {
                        useRewardPointsAndDecrease($username, $restAfterModuloSubtraction);
                        sendMailCheckoutInformation($username, $first_name, $last_name, $cart_items, $last_id, $shipping_company, $shipping_price, $totalAmountAfterSale, $reward_points, $salesForPromoCodes);
                        $conn->commit();
                        header("Location: /thank-you.php?last_id=" . $last_id);
                    } else {
                        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                    }
                } else {
                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                }
            } else {
                echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
            }
        } else {
            echo json_encode(array('error' => 'No items in cart.'));
        }
    } catch (Exception $e) {
        $conn->rollback();
        // echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.', 'details' => $e->getMessage()));
        // throw new Exception("Error: " . $e->getMessage());
        // header("Location: /checkout.php");
        // exit();

        $errorMsg = 'General error: ' . $e->getMessage();
        $errorTrace = $e->getTraceAsString();
        echo json_encode([
            'error' => $errorMsg,
            'details' => $errorTrace
        ]);
        error_log("Error: " . $errorMsg . "\n" . $errorTrace); // Log to server error log
    }
} else {
    echo json_encode(array('error' => 'Please fill out all fields.'));
}
