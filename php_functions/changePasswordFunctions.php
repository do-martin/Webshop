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
require_once $path . "/php_functions/phpMailer.php";
require_once $path . "/php_functions/generalFunctions.php";

function createNewLogByUsername($username, $user_current_screen_res, $user_current_os)
{
    global $conn;
    $sql_insert = "INSERT INTO logs(
        activity,
        last_login,
        last_screen_res,
        last_op_system,
        id_customer)
        VALUES(1, GETDATE(), :user_screen_res, :user_op_system, (SELECT id_customer FROM customers WHERE username = :username))";

    if ($stmt = $conn->prepare($sql_insert)) {

        $stmt->bindParam(':user_screen_res', $user_current_screen_res, PDO::PARAM_STR);
        $stmt->bindParam(':user_op_system', $user_current_os, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return ["success" => true];
        } else {
            echo "Oops! Something went wrong: " . $stmt->error;
        }
    } else {
        echo "Oops! Something went wrong: " . $conn->error;
    }
    return ["success" => false];
}

function updatePassword($username, $password)
{
    global $conn;

    $sql_update = "UPDATE customers
    SET pw = :password
    WHERE username = :username";

    if ($stmt = $conn->prepare($sql_update)) {
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return ["success" => true];
        } else {
            echo "Oops! Something went wrong: " . $stmt->error;
        }
    } else {
        echo "Oops! Something went wrong: " . $conn->error;
    }
    return ["success" => false];
}

function checkForExistingLog($username)
{
    global $conn;

    $sql_check = "SELECT id_logs FROM logs WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username)";

    if ($stmt = $conn->prepare($sql_check)) {
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($results) == 1) {
                return ["success" => true];
            } else {
                return ["success" => false];
            }
        } else {
            return ["success" => false, "error" => $stmt->error];
        }
    } else {
        return ["success" => false, "error" => $conn->error];
    }
    return ["success" => false];
}

function verifyNewPassword($username, $password)
{
    global $conn;
    $sql_verify = " SELECT pw FROM customers WHERE username = :username AND pw = :password";

    if ($stmt = $conn->prepare($sql_verify)) {
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($results) == 1) {
                return ["success" => false];
            } else {
                return ["success" => true];
            }
        } else {
            return ["success" => false, "error" => $stmt->error];
        }
    } else {
        return ["success" => false, "error" => $conn->error];
    }
    return ["success" => false];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $screen_height = $screen_width = 0;
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    $username = $_SESSION["username"];
    $password = $data["password"];
    $screen = $data["screeen_width_height"];
    $os = $data["os"];

    if ($data === null) {
        echo json_encode(["success" => false, "error" => "Invalid JSON data"]);
        exit();
    }

    if (isset($_SESSION["logs"]) && $_SESSION["logs"] == false) {

        $resultVerification = verifyNewPassword($username, $password)["success"];

        if (!$resultVerification) {
            header('Content-Type: application/json');
            echo json_encode(["success" => false, "error" => "Password already in use. Please choose a different one."]);
            exit();
        }

        if (checkForExistingLog($username)["success"]) {
            $_SESSION["logs"] = true;
            updatePassword($username, $password);
            header('Content-Type: application/json');
            echo json_encode(["success" => true, "message" => "Password changed successfully"]);
        } else {
            if (createNewLogByUsername($username, $screen, $data["os"])["success"] && updatePassword($username, $password)["success"]) {
                $_SESSION["loggedin"] = true;
                header('Content-Type: application/json');
                echo json_encode(["success" => true, "message" => "Password changed successfully"]);
            } else {
                echo json_encode(["success" => false, "error" => $conn->error]);
                exit();
            }
        }
        // }
    }else{
        if (checkForExistingLog($username)["success"]) {
            $_SESSION["logs"] = true;
            updatePassword($username, $password);
            header('Content-Type: application/json');
            echo json_encode(["success" => true, "message" => "Password changed successfully"]);
        } else {
            if (createNewLogByUsername($username, $screen, $data["os"])["success"] && updatePassword($username, $password)["success"]) {
                $_SESSION["loggedin"] = true;
                header('Content-Type: application/json');
                echo json_encode(["success" => true, "message" => "Password changed successfully"]);
            } else {
                echo json_encode(["success" => false, "error" => $conn->error]);
                exit();
            }
        }
    }
}
