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
require_once $path . "/models/productModel.php";
require_once $path . "/php_functions/checkForFirstLogin.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false) {
    header("location: /login.php");
    exit;
}

switch ($_POST["action_token"]) {
    case "updateMainData":
        if (isset($_SESSION["username"], $_POST["first_name"], $_POST["last_name"], $_POST["address"], $_POST["postal_code"], $_POST["location"], $_POST["country"], $_POST["gender"])) {

            $username = $_SESSION["username"];
             //Test
             $sanitized_email = filter_var($_SESSION["username"], FILTER_SANITIZE_EMAIL);
             if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                 $username = $sanitized_email;
             } else {
                 header("location: /logout.php");
                 exit();
             }

            $first_name = strip_tags($_POST["first_name"]);
            $last_name = strip_tags($_POST["last_name"]);
            $address = strip_tags($_POST["address"]);
            $postal_code = strip_tags($_POST["postal_code"]);
            $location = strip_tags($_POST["location"]);
            $country = strip_tags($_POST["country"]);
            $gender = strip_tags($_POST["gender"]);
            if (updateCustomerMainData($username, $first_name, $last_name, $address, $postal_code, $location, $country, $gender)["success"]) {
                header("location: /welcome.php");
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Please fill in all fields";
        }
        break;
    case "updatePassword":
        break;
    case "updateTwoFA":
        break;
    case "deleteAccount":
        break;
    default:
        echo "Invalid action token";
        break;
}

function updateCustomerMainData($username, $first_name, $last_name, $address, $postal_code, $location, $country, $gender)
{
    global $conn;
    $updateSql = "UPDATE customers SET first_name = :first_name, last_name = :last_name, street = :street, postal_code = :postal_code, location = :location, country = :country, gender = :gender WHERE username = :username";
    $stmt = $conn->prepare($updateSql);
    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindParam(':street', $address, PDO::PARAM_STR);
    $stmt->bindParam(':postal_code', $postal_code, PDO::PARAM_STR);
    $stmt->bindParam(':location', $location, PDO::PARAM_STR);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $_SESSION["first_name"] = $first_name;
        $_SESSION["last_name"] = $last_name;
        $_SESSION["username"] = $username;

        return ["success" => true, "message" => "Data updated successfully"];
    } else {
        return ["success" => false, "message" => "Error: " . $conn->error];
    }
}
