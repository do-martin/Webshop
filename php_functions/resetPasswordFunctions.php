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

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
    header("location: /welcome.php");
    exit;
}

if (!isset($_SESSION['reset_password_attempts'])) {
    $_SESSION['reset_password_attempts'] = 0;
    $_SESSION['last_attempt_time_reset_password'] = time();
}

if (isset($_SESSION['reset_password_attempts']) && isset($_SESSION['last_attempt_time_reset_password']) && time() - $_SESSION['last_attempt_time_reset_password'] < 6000) {
    $_SESSION['reset_password_attempts']++;
    $_SESSION['last_attempt_time_reset_password'] = time();
}

if (isset($_SESSION['reset_password_attempts']) && isset($_SESSION['last_attempt_time_reset_password']) && time() - $_SESSION['last_attempt_time_reset_password'] > 360000) {
    $_SESSION['reset_password_attempts'] = 0;
}

function resetTwoFA($username)
{
    global $conn;
    $sql = "UPDATE customers
    SET two_factor_auth = 0
    WHERE username = :username";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $hash = $last_name  = $gender = $last_login = $first_name = "";

    $json_data = file_get_contents('php://input');

    $data = json_decode($json_data, true);

    if ($data === null) {
        echo "Error: Invalid JSON data";
        exit;
    }

    $username = $data['username'];
    $hash = generatePassword();
    echo "$username";

    if (!empty($username) && !empty($hash)) {
        $sql = "SELECT 
        c.last_name, 
        c.gender, 
        l.last_login,
        c.first_name 
    FROM 
        customers as c
    LEFT JOIN
        logs AS l ON l.id_customer = c.id_customer
    WHERE 
        c.username = :username";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $param_username = $username;

            if ($stmt->execute()) {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($results) == 1) {
                    $last_name = $results[0]['last_name'];
                    $gender = $results[0]['gender'];
                    $last_login = $results[0]['last_login'];
                    $first_name = $results[0]['first_name'];
                    $_SESSION["username"] = $username;

                    if (!resetTwoFA($username)) {
                        echo "Oops! Something went wrong. Please try again later: " . $stmt->error;
                    }

                    $sql_update = "UPDATE customers SET pw = :pw WHERE username = :username";
                    if ($stmt = $conn->prepare($sql_update)) {
                        // $stmt->bind_param("ss", $param_hash, $param_username);
                        $stmt->bindParam(':pw', $param_hash, PDO::PARAM_STR);
                        $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
                        $param_hash = trim(hash('sha512', $hash)); // Creates a password hash

                        $param_username = $username;
                        if ($stmt->execute()) {
                            sendMail($username, $first_name, $last_name, $hash, false);
                        } else {
                            echo "Oops! Something went wrong. Please try again later: " . $stmt->error;
                        }
                    }
                    header("location: /login.php");
                    exit;
                } else {
                    echo "Oops! Something went wrong. Please try again later: " . $stmt->error;
                }
            } else {
                echo "Oops! Something went wrong. Please try again later: " . $stmt->error;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later: " . $conn->error;
        }
    }
}
