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

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time_login'] = time();
}

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/php_functions/generalFunctions.php";
require_once $path . "/php_functions/rewardsFunctions.php";
require_once $path . "/php_functions/twoFactorAuthFunctions.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
    header("location: welcome.php");
    exit;
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $password = $last_name  = $gender = $last_login = "";
    $username_err = $password_err = $login_err = "";
    $user_current_os = $user_current_screen_res = "";

    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if ($data === null) {
        echo "Error: Invalid JSON data";
        exit;
    }

    $username = $data['username'];
    $password = $data['password'];
    $user_current_screen_res = $data['screen_width'] . ' x ' . $data['screen_height'];
    $user_current_os = $data['os'];

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT
            c.id_customer,
            c.username,
            c.pw,
            c.last_name,
            c.gender,
            l.last_login
        FROM customers AS c
        LEFT JOIN logs AS l ON c.id_customer = l.id_customer
        WHERE c.username = :username";


        if ($stmt = $conn->prepare($sql)) {
            $param_username = $username;
            $stmt->bindValue(":username", $param_username, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($results) == 0) {
                    header('Content-Type: application/json');
                    $redirectUrl = "/login.php?login_err=Invalid username or password.";
                    $redirectData = array(
                        "redirectUrl" => $redirectUrl,
                        "TwoFA" => "deactivated"
                    );
                    echo json_encode($redirectData);
                    exit;
                }

                $id_customer = $results[0]['id_customer'];
                $username = $results[0]['username'];
                $hashed_password = $results[0]['pw'];
                $last_name = $results[0]['last_name'];
                $gender = $results[0]['gender'];
                $last_login = $results[0]['last_login'];

                if ($password == $hashed_password) {

                    if (isset($_SESSION['login_attempts'])) {
                        $_SESSION['login_attempts'] = 0;
                        $_SESSION['last_attempt_time_login'] = time();
                    }

                    $user2FAStatus = getUserTwoFAStatus($username);
                    if ($user2FAStatus["two_factor_auth"] == 1) {
                        $redirectData = array(
                            "redirectUrl" => "/login.php?user_id=" . urlencode($username) . "&modal=open",
                            "TwoFA" => "activated",
                            "secret" => $user2FAStatus["auth_key"]
                        );
                        $_SESSION["username"] = $username;

                        header('Content-Type: application/json');
                        echo json_encode($redirectData);
                        exit;
                    }

                    date_default_timezone_set('Europe/Berlin');
                    $current_time = date("Y-m-d H:i:s");

                    updateRewardPointsSQL($username, 2);

                    if ($last_login == null || $last_login == "") {
                        $_SESSION["id_customer"] = $id_customer;
                        $_SESSION["username"] = $username;
                        $_SESSION["loggedin"] = true;
                        $_SESSION["logs"] = false;

                        $redirectUrl = "/change-password.php?user_id=" . urlencode($id_customer) . "&username=" . urlencode($username);

                        $redirectData = array(
                            "redirectUrl" => $redirectUrl,
                            "TwoFA" => "deactivated"
                        );

                        header('Content-Type: application/json');
                        echo json_encode($redirectData);
                        exit;
                    } else {
                        updateLogs($username, $user_current_os, $user_current_screen_res, $current_time);
                    }

                    $_SESSION["id_customer"] = $id_customer;
                    $_SESSION["username"] = $username;
                    $_SESSION["loggedin"] = true;

                    header('Content-Type: application/json');
                    $redirectUrl = "/welcome.php?user_id=" . urlencode($id_customer)
                        . "&username=" . urlencode($username)
                        . "&last_name=" . urlencode($last_name)
                        . "&last_login=" . urlencode($last_login)
                        . "&gender=" . urlencode($gender)

                        . "&user_current_os=" . urlencode($user_current_os)
                        . "&user_current_screen_res=" . urlencode($user_current_screen_res);
                    $redirectData = array(
                        "redirectUrl" => $redirectUrl,
                        "TwoFA" => "deactivated"
                    );
                    echo json_encode($redirectData);
                    exit;
                } else {

                    if (isset($_SESSION['login_attempts'])) {
                        $_SESSION['login_attempts']++;
                        $_SESSION['last_attempt_time_login'] = time();
                    }

                    header('Content-Type: application/json');
                    $redirectUrl = "/login.php?login_err=Invalid username or password.";
                    $redirectData = array(
                        "redirectUrl" => $redirectUrl,
                        "TwoFA" => "deactivated"
                    );
                    echo json_encode($redirectData);
                    exit;
                }
            }
        } else {
            echo "Oops! Something went wrong. Please try again later: " . $stmt->error . $conn->error_log;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later: " . $stmt->error . $conn->error_log;
    }
}
