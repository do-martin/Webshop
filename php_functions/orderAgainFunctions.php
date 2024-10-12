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
require_once $path . "/php_functions/userFunctions.php";
require_once $path . "/models/cartModel.php";
require_once $path . "/php_functions/phpMailer.php";
// require_once $path . "/php_functions/sqlSelects.php";
require_once $path . "/php_functions/rewardsFunctions.php";

$username = $_SESSION['username'];

if (isset($_POST['action']) && $_POST['action'] == 'order_again') {

    if (isset($_POST['orderID'])) {
        $orderID = $_POST['orderID'];
        error_log("Order with ID: " . $orderID);
        $conn->beginTransaction();
        try {
            $worksIdHeaderAndPosition = copyInvoiceHeaderInvoicePosition($orderID);

            if ($worksIdHeaderAndPosition["success"]) {

                $inserted_id_header = $worksIdHeaderAndPosition["invoice_header_id"];
                $inserted_id_position = $worksIdHeaderAndPosition["invoice_position_id"];

                $sql_get_invoice_data_and_user_data = "SELECT 
            p.id_item_num, p.prod_name, p.price, p.item_inventory, p.category, p.path_img, ip.amount,
            c.first_name, c.last_name, ih.shipping_company, ih.shipping_price,
            p.gender
            FROM invoice_position AS ip
            JOIN products AS p ON ip.id_item_num = p.id_item_num
            JOIN invoice_header as ih ON ip.id_invoice_number = ih.id_invoice_number
            JOIN customers AS c ON c.id_customer = ih.id_customer
            WHERE ip.id_invoice_number = :id_invoice_number";


                if ($stmt_select = $conn->prepare($sql_get_invoice_data_and_user_data)) {
                    $stmt_select->bindParam(':id_invoice_number', $param_invoice_number, PDO::PARAM_INT);
                    $param_invoice_number = $inserted_id_header;

                    if ($stmt_select->execute()) {
                        $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

                        if (count($results) >= 1) {

                            // $stmt_select->bind_result($id_item_num, $prod_name, $price, $item_inventory, $category, $path, $amount, $first_name, $last_name, $shipping_company, $shipping_price, $gender);
                            $cart_items = array();
                            foreach ($results as $result) {
                                $id_item_num = $result['id_item_num'];
                                $prod_name = $result['prod_name'];
                                $price = $result['price'];
                                $item_inventory = $result['item_inventory'];
                                $category = $result['category'];
                                $path = $result['path_img'];
                                $amount = $result['amount'];
                                $first_name = $result['first_name'];
                                $last_name = $result['last_name'];
                                $shipping_company = $result['shipping_company'];
                                $shipping_price = $result['shipping_price'];
                                $gender = $result['gender'];
                                $cart = new Cart($id_item_num, $prod_name, $price, $amount, $item_inventory, $category, $path, $gender);
                                $cart_items[] = $cart;
                            }

                            $itemsNumAndAmount = getBoughtItemsWithAmount($inserted_id_header);
                            foreach ($itemsNumAndAmount as $numAndAmount) {
                                if (decreaseItemsByItemNumAndAmount($numAndAmount["id_item_num"], $numAndAmount["amount"])) {
                                    continue;
                                } else {
                                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                                    return;
                                }
                            }

                            $newTotalAmount = getNewTotalAmount($inserted_id_header);

                            sendMailCheckoutInformation($_SESSION["username"], $first_name, $last_name, $cart_items, $inserted_id_header, $shipping_company, $shipping_price, ($newTotalAmount - $shipping_price), 0, 0);
                            updateRewardPointsSQL($_SESSION["username"], 25);
                            header('Content-Type: application/json');
                            echo json_encode("/thank-you.php?last_id=" . $worksIdHeaderAndPosition["invoice_header_id"]);
                            $conn->commit();
                            return;
                        } else {
                            echo json_encode(array('error' => 'No items in cart.'));
                        }
                    } else {
                        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                    }
                } else {
                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                }
            }
        } catch (Exception $e) {
            $conn->rollback();
            // echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
            echo json_encode("/checkout.php");
            exit();
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Order ID not provided.'
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        'success' => false,
        'message' => 'No action specified.'
    );
    echo json_encode($response);
}

function copyInvoiceHeaderInvoicePosition($orderID)
{
    global $conn;
    $param_invoice_number = "";
    $param_copy_from_invoice_number = "";

    $inserted_id_header = "";
    $inserted_id_position = "";

    // $sql_copy_invoice_header = "INSERT INTO invoice_header (order_date, shipping_company, total_amount, id_customer, shipping_price, used_points, subtotal, sales_promo_code)
    // SELECT NOW(), shipping_company, (total_amount+(used_points / 100 * 0.1)+sales_promo_code), id_customer, shipping_price, 0, subtotal, 0
    // FROM invoice_header
    // WHERE id_invoice_number = :id_invoice_number";

    $sql_copy_invoice_header = "INSERT INTO invoice_header 
    (order_date, shipping_company, total_amount, id_customer, shipping_price, used_points, subtotal, sales_promo_code)
SELECT 
    GETDATE(), 
    shipping_company, 
    (total_amount + (used_points / 100.0 * 0.1) + sales_promo_code),
    id_customer, 
    shipping_price, 
    0, 
    subtotal, 
    0
FROM 
    invoice_header
WHERE 
    id_invoice_number = :id_invoice_number;
";

    if ($stmt_select = $conn->prepare($sql_copy_invoice_header)) {
        $stmt_select->bindParam(':id_invoice_number', $param_invoice_number, PDO::PARAM_INT);
        $param_invoice_number = $orderID;

        if ($stmt_select->execute()) {
            $inserted_id_header = $conn->lastInsertId();

            $sql_copy_invoice_positions = "INSERT INTO invoice_position(id_invoice_number, id_item_num, amount, price)
            SELECT :id_invoice_number, id_item_num, amount, price
            FROM invoice_position
            WHERE id_invoice_number = :param_copy_from_invoice_number";
            if ($stmt_select = $conn->prepare($sql_copy_invoice_positions)) {
                $stmt_select->bindParam(':id_invoice_number', $param_invoice_number, PDO::PARAM_INT);
                $stmt_select->bindParam(':param_copy_from_invoice_number', $param_copy_from_invoice_number, PDO::PARAM_INT);

                $param_invoice_number = $inserted_id_header;
                $param_copy_from_invoice_number = $orderID;

                if ($stmt_select->execute()) {
                    $inserted_id_position = $conn->lastInsertId();

                    $returnArray = array(
                        "success" => true,
                        "invoice_header_id" => $inserted_id_header,
                        "invoice_position_id" => $inserted_id_position
                    );
                    return $returnArray;
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
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
}

function getBoughtItemsWithAmount($inserted_id_header)
{
    global $conn;
    $param_invoice_number = $id_item_num = $amount = 0;

    $sql_bought_items = "SELECT p.id_item_num, p.amount
    FROM invoice_header as h
    JOIN invoice_position as p ON h.id_invoice_number = p.id_invoice_number
    WHERE h.id_invoice_number = :id_invoice_number";
    if ($stmt_select = $conn->prepare($sql_bought_items)) {
        $stmt_select->bindParam(':id_invoice_number', $param_invoice_number, PDO::PARAM_INT);
        $param_invoice_number = $inserted_id_header;

        if ($stmt_select->execute()) {
            $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) >= 1) {
                foreach ($results as $result) {
                    $id_item_num = $result['id_item_num'];
                    $amount = $result['amount'];
                    $item_id_amount[] = array("id_item_num" => $id_item_num, "amount" => $amount);
                }
                return $item_id_amount;
            } else {
                echo json_encode(array('error' => 'No items in cart.'));
            }
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
}

function decreaseItemsByItemNumAndAmount($item_num, $item_amount)
{
    global $conn;
    $sql = "UPDATE products SET item_inventory = item_inventory - :amount WHERE id_item_num = :item_num";
    $param_amount = $param_item_num = 0;

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(':amount', $param_amount, PDO::PARAM_INT);
        $stmt->bindParam(':item_num', $param_item_num, PDO::PARAM_INT);
        $param_amount = $item_amount;
        $param_item_num = $item_num;

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getNewTotalAmount($inserted_id_header)
{
    global $conn;
    $param_invoice_number = $total_amount = 0;

    $sql_get_total_amount = "SELECT total_amount FROM invoice_header WHERE id_invoice_number = :id_invoice_number";
    if ($stmt_select = $conn->prepare($sql_get_total_amount)) {
        $stmt_select->bindParam(':id_invoice_number', $param_invoice_number, PDO::PARAM_INT);
        $param_invoice_number = $inserted_id_header;

        if ($stmt_select->execute()) {
            $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) >= 1) {
                $total_amount = $results[0]['total_amount'];
                return $total_amount;
            } else {
                echo json_encode(array('error' => 'No items in cart.'));
            }
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
}
