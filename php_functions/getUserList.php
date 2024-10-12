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
require_once $path . "/models/customerModel.php";

$user_list = [];

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $sql = "SELECT 
    c.username, 
    c.first_name, 
    c.last_name, 
    c.street, 
    c.postal_code, 
    c.location, 
    c.country, 
    c.gender 
FROM 
    logs AS l
JOIN
    customers AS c ON l.id_customer = c.id_customer
WHERE 
    l.activity = 1";

    if ($stmt = $conn->prepare($sql)) {
        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


            if (count($results) >= 1) {
                foreach ($results as $result) {
                    if ($result['username'] != ($_SESSION["username"])) {
                        $user = new Customer($result['username'], $result['first_name'], $result['last_name'], $result['street'], $result['postal_code'], $result['location'], $result['country'], $results['gender']);
                        $user_list[] = $user;
                    }
                }
                header('Content-Type: application/json');
                echo json_encode($user_list);
                exit;
            } else {
                echo json_encode([]);
                exit;
            }
        } else {
            echo json_encode(["error" => "Error executing query: " . $stmt->error]);
        }
    } else {
        echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "You are not logged in."]);
    exit;
}
