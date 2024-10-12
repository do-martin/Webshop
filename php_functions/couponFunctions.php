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

$username = $_SESSION["username"];

if (isset($_POST['action']) && !empty($_POST['action']) && isset($_SESSION["username"]) && !empty($_SESSION["username"]) && isset($_POST['coupon']) && !empty($_POST['coupon'])) {
    $action = $_POST['action'];

    $id_coupon = $sale = "";

    switch ($action) {
        case 'use_coupon':
            $sql_get_coupon = "SELECT id_promo_code, promo_code, sale
                                FROM promo_codes 
                                WHERE promo_code = :promo_code 
                                AND tries > 0 
                                ORDER BY sale DESC
                                LIMIT 1";

            if ($stmt_select = $conn->prepare($sql_get_coupon)) {
                $stmt_select->bindParam(':promo_code', $param_coupon, PDO::PARAM_STR);
                $param_coupon = trim($_POST["coupon"]);

                if ($stmt_select->execute()) {
                    $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
                    $coupon_data = array();

                    if (count($results) >= 1) {
                        $coupon_data = $results[0]['id_promo_code'];
                        $coupon_data = $results[0]['promo_code'];
                        $coupon_data = $results[0]['sale'];
                        header('Content-Type: application/json');
                        echo json_encode($coupon_data);
                    } else {
                        echo json_encode(array('error' => 'No coupons.'));
                    }
                } else {
                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                }
            } else {
                echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
            }
            break;
        default:
            echo json_encode(array('error' => 'Invalid action.'));
    }
}
