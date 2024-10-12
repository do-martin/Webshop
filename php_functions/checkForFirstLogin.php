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
// require_once $path . "/php_functions/sqlSelects.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $sql = "SELECT
            c.id_customer,
            c.username,
            l.last_login
        FROM customers AS c
        LEFT JOIN logs AS l ON c.id_customer = l.id_customer
        WHERE c.username = :username";


    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);


        $param_username = $username;

        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) == 1) {
                $id_customer = $results[0]["id_customer"];
                $username = $results[0]["username"];
                $last_login = $results[0]["last_login"];
                if ($last_login == null || $last_login == "") {
                    header("Location: /change-password.php?user_id=" . urlencode($id_customer) . "&username=" . urlencode($username));
                }
            }
        } else {
            echo "Oops! Something went wrong. Please try again later: " . $stmt->error . $conn->error_log;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later: " . $stmt->error . $conn->error_log;
    }
}
