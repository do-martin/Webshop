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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/models/cartModel.php";
require_once $path . "/php_functions/userFunctions.php";

function getCartItemsByUsername($username)
{
    global $conn;
    $param_username = "";
    $id_item_num = $prod_name = $price = $item_inventory = $category = $path = $amount = $gender = "";
    $cart_items = array();

    $sql_get_cart_items = "SELECT 
    p.id_item_num,
    p.prod_name,
    p.price,
    p.item_inventory,
    p.category,
    p.path_img,
    ca.amount,
    p.gender
    FROM
    products as p
    JOIN 
    carts as ca ON ca.id_item_num = p.id_item_num 
    JOIN
    customers as cu ON cu.id_customer = ca.id_customer
    WHERE 
    ca.id_customer = (SELECT id_customer FROM customers WHERE username = :username)";

    if ($stmt_select = $conn->prepare($sql_get_cart_items)) {
        $stmt_select->bindValue(':username', $username, PDO::PARAM_STR);

        if ($stmt_select->execute()) {
            $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) >= 1) {
                foreach($results as $row){
                    $cart = new Cart($row['id_item_num'], $row['prod_name'], $row['price'], $row['amount'], $row['item_inventory'], $row['category'], $row['path_img'], $row['gender']);
                    $cart_items[] = $cart;
                }
                return $cart_items;
            } else {
                return null;
            }
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
}

function deleteCartItemsByUsernameAndItemNumber($username, $id_item_num)
{
    global $conn;

    $sql_delete = "DELETE FROM carts WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username) AND id_item_num = :id_item_num";

    if ($stmt_delete = $conn->prepare($sql_delete)) {
        
        $stmt_delete->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt_delete->bindValue(':id_item_num', $id_item_num, PDO::PARAM_INT);

        if ($stmt_delete->execute()) {
            return true;
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
}

function deleteCartItemsByUsername($username)
{
    global $conn;
    $param_username = "";

    $sql_delete_cart = "DELETE FROM carts WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username)";
    if ($stmt_delete = $conn->prepare($sql_delete_cart)) {
        $stmt_delete->bindValue(':username', $username, PDO::PARAM_STR);

        if ($stmt_delete->execute()) {
            return ["success" => true];
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
    return ["success" => false];
}

function insertOrUpdateCart($username, $id_item_num, $prod_name, $amount)
{
    global $conn;
    // $cart_items = array();

    $sql_select = "SELECT id_cart FROM carts WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username)
    AND id_item_num = (SELECT id_item_num FROM products WHERE id_item_num = :id_item_num AND prod_name = :prod_name)";

    if ($stmt_select = $conn->prepare($sql_select)) {
        $stmt_select->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt_select->bindValue(':id_item_num', $id_item_num, PDO::PARAM_INT);
        $stmt_select->bindValue(':prod_name', $prod_name, PDO::PARAM_STR);

        if ($stmt_select->execute()) {
            $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
            if (count($results) >= 1) {

                $sql_update = "UPDATE carts SET amount = :amount WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username) AND id_item_num = :id_item_num";
                if ($stmt_update = $conn->prepare($sql_update)) {
                    $stmt_update->bindValue(':username', $username, PDO::PARAM_STR);
                    $stmt_update->bindValue(':id_item_num', $id_item_num, PDO::PARAM_INT);
                    $stmt_update->bindValue(':amount', $amount, PDO::PARAM_INT);

                    if ($stmt_update->execute()) {
                        return true;
                    } else {
                        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                    }
                } else {
                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                }
            } else {
                $sql_insert = "INSERT INTO carts (id_customer, id_item_num, amount) VALUES ((SELECT id_customer FROM customers WHERE username = :username), :id_item_num, :amount)";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bindValue(':username', $username, PDO::PARAM_STR);
                    $stmt_insert->bindValue(':id_item_num', $id_item_num, PDO::PARAM_INT);
                    $stmt_insert->bindValue(':amount', $amount, PDO::PARAM_INT);
                    if ($stmt_insert->execute()) {
                        return true;
                    } else {
                        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                    }
                } else {
                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                }
            }
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
}

if (
    isset($_POST['action']) && !empty($_POST['action']) && isset($_SESSION["username"]) && !empty($_SESSION["username"])
    && isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true
) {
    $action = $_POST['action'];
    $id_customer = $prod_name = $id_item_num = $amount = $price = "";
    $username = trim($_SESSION["username"]);

    switch ($action) {
        case 'getCartItems':
            if (isset($_SESSION["username"])) {
                $cart_items = getCartItemsByUsername($username);
                header('Content-Type: application/json'); // Set the Content-Type header to JSON
                if ($cart_items != null) {
                    echo json_encode($cart_items);
                } else {
                    echo json_encode(array('error' => 'No items in cart.'));
                }
            }
            break;
        case 'insertOrUpdateCart':
            if (isset($_POST['id_item_num'], $_POST['prod_name'], $_POST['amount'], $_POST['price'], $_SESSION["username"])) {
                $id_item_num = $_POST['id_item_num'];
                $prod_name = $_POST['prod_name'];
                $amount = $_POST['amount'];
                if (insertOrUpdateCart($username, $id_item_num, $prod_name, $amount)) {
                    echo json_encode(true);
                } else {
                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                }
            }
            break;
        case 'deleteCartItem':
            if (isset($_POST['id_item_num'], $_SESSION["username"])) {
                $id_item_num = $_POST['id_item_num'];
                $username = trim($_SESSION["username"]);
                echo json_encode(deleteCartItemsByUsernameAndItemNumber($username, $id_item_num));
            }
            break;
        default:
            echo json_encode(array('error' => 'Invalid action.'));
    }
}
