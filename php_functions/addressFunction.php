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

function createAddress($username, $gender, $first_name, $last_name, $address, $postal_code, $location, $country)
{
    global $conn;

    $sqlCreateAddress = "INSERT INTO addresses (first_name, last_name, gender, street, zip, location, country, main_address, id_customer) 
    VALUES (:first_name, :last_name, :gender, :street, :zip, :location, :country, 0, (SELECT id_customer FROM customers WHERE username = :customer))";

    $stmt = $conn->prepare($sqlCreateAddress);

    if ($stmt === false) {
        echo "Error: " . $conn->error;
    }

    // $stmt->bind_param("ssssssss", $first_name, $last_name, $gender, $address, $postal_code, $location, $country, $username);
    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':street', $address, PDO::PARAM_STR);
    $stmt->bindParam(':zip', $postal_code, PDO::PARAM_STR);
    $stmt->bindParam(':location', $location, PDO::PARAM_STR);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $stmt->bindParam(':customer', $username, PDO::PARAM_STR);

    $success = $stmt->execute();

    if ($success == false) {
        echo "Error: " . $stmt->error;
        exit();
    }

    return ["success" => $success];
}

function updateAddress($address_id, $first_name, $last_name, $gender, $address, $postal_code, $location, $country)
{
    global $conn;

    $sqlUpdateAddress = "UPDATE addresses 
                        SET first_name = :first_name, 
                            last_name = :last_name, 
                            gender = :gender, 
                            street = :address, 
                            zip = :postal_code, 
                            location = :location, 
                            country = :country 
                        WHERE id_address = :address_id";

    $stmt = $conn->prepare($sqlUpdateAddress);

    if ($stmt === false) {
        return ["error" => "Error: " . $conn->error];
    }

    // $stmt->bind_param("sssssssi", $first_name, $last_name, $gender, $address, $postal_code, $location, $country, $address_id);
    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':postal_code', $postal_code, PDO::PARAM_STR);
    $stmt->bindParam(':location', $location, PDO::PARAM_STR);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $stmt->bindParam(':address_id', $address_id, PDO::PARAM_INT);

    $success = $stmt->execute();

    if ($success === false) {
        return ["error" => "Error: " . $stmt->error];
    }

    return ["success" => $success];
}

if (session_status() === PHP_SESSION_NONE) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/php_functions/userFunctions.php";

if (
    isset($_SESSION["username"]) && isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true
    && isset($_POST["gender"]) && isset($_POST["first_name"]) && isset($_POST["last_name"])
    && isset($_POST["address"]) && isset($_POST["postal_code"]) && isset($_POST["location"]) && isset($_POST["country"])
    && isset($_POST["action_token"]) && !empty($_POST["action_token"])
) {
    $username = $_SESSION["username"];
    $gender = strip_tags($_POST["gender"]);
    $first_name = strip_tags($_POST["first_name"]);
    $last_name = strip_tags($_POST["last_name"]);
    $address = strip_tags($_POST["address"]);
    $postal_code = strip_tags($_POST["postal_code"]);
    $location = strip_tags($_POST["location"]);
    $country = strip_tags($_POST["country"]);

    switch ($_POST["action_token"]) {

        case "createAddress":
            if (createAddress($username, $gender, $first_name, $last_name, $address, $postal_code, $location, $country)["success"] === true) {
                header("Location: ../index.php");
            } else {
                echo "error";
            }
            echo "createAddress";
            break;
        case "updateAddress":
            echo "updateAddress";
            break;
        case "deleteAddress":
            echo "deleteAddress";
            break;
        default:
            echo "default";
            break;
    }
}
