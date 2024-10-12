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
require_once $path . "/vendor/autoload.php";
require_once $path . "/php_functions/generalFunctions.php";

if (isset($_POST["action"])) {
    $username = $_SESSION["username"];
    $action = $_POST["action"];
    switch ($action) {
        case "activate":
            $secrQrCode = create2FAKey();
            $userStatus = getUserTwoFAStatus($username);
            $qrCode = activate2FA($username, $userStatus, $secrQrCode);
            echo json_encode($qrCode);
            break;
        case "deactivate":
            if (deactivate2FA($username)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
            break;
        case "verify":
            if (checkCode($_POST["secret"], $_POST["code"])) {
                updateLogs($_SESSION["username"], $_POST["os"], $_POST["screen_width_height"], date("Y-m-d H:i:s"));
                $_SESSION["loggedin"] = true;
                header('Content-Type: application/json');
                $redirectUrl = "../welcome.php";
                $redirectData = array(
                    "redirectUrl" => $redirectUrl,
                    "success" => true
                );
                echo json_encode($redirectData);
                exit;
            } else {
                $returnArray = ["success" => false];
                echo json_encode($returnArray);
                exit;
            }
            break;
        case "setup":
            $secrQrCode = create2FAKey();
            $userStatus = getUserTwoFAStatus($username);
            $qrCode = activate2FA($username, $userStatus, $secrQrCode);
            echo json_encode($qrCode);
            break;
        default:
            echo json_encode("Error");
            break;
    }
}

function updateLogs($username, $user_current_os, $user_current_screen_res, $current_time)
{
    global $conn;
    $param_current_time = $param_user_current_screen_res = $param_user_current_os = $param_username = "";

    $sql_update = "UPDATE l
SET l.last_login = :current_time,
    l.activity = 1, -- SQL Server verwendet `1` für `true`
    l.last_screen_res = :user_current_screen_res,
    l.last_op_system = :user_current_os
FROM logs AS l
JOIN customers AS c ON l.id_customer = c.id_customer
WHERE c.username = :username";

    if ($stmt = $conn->prepare($sql_update)) {
        $stmt->bindValue(':current_time', $current_time, PDO::PARAM_STR);
        $stmt->bindValue(':user_current_screen_res', $user_current_screen_res, PDO::PARAM_STR);
        $stmt->bindValue(':user_current_os', $user_current_os, PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return ["success" => true];
        } else {
            echo "Something went wrong: " . $stmt->error;
        }
    }
    return ["success" => false];
}

function create2FAKey()
{
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once $path . "/vendor/autoload.php";

    $ga = new PHPGangsta_GoogleAuthenticator();

    $secret = $ga->createSecret();
    $qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
    $oneCode = $ga->getCode($secret);

    $checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance

    return ["secret" => $secret, "qrCodeUrl" => $qrCodeUrl, "oneCode" => $oneCode];
}

function getCodeBySecret($secret)
{
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once $path . "/vendor/autoload.php";

    $ga = new PHPGangsta_GoogleAuthenticator();
    $oneCode = $ga->getCode($secret);

    return $oneCode;
}

function checkCode($secret, $code)
{
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once $path . "/vendor/autoload.php";

    $ga = new PHPGangsta_GoogleAuthenticator();
    $checkResult = $ga->verifyCode($secret, $code, 2);    // 2 = 2*30sec clock tolerance

    return $checkResult;
}

function getUserTwoFAStatus($username)
{
    global $conn;
    $two_factor_auth = "";
    $key = "";

    try {
        $sql = "SELECT c.two_factor_auth,
                auth.auth_key
                FROM customers AS c
                LEFT JOIN two_factor_authentification AS auth ON auth.id_customer = c.id_customer
                WHERE c.username = :username";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $two_factor_auth = $results[0]['two_factor_auth'];
        $key = $results[0]['auth_key'];

        return ["two_factor_auth" => $two_factor_auth, "auth_key" => $key];
    } catch (Exception $e) {
        echo "Fehler: " . $e->getMessage();
        return null;
    }
}

function activate2FA($username, $status, $secretQrOneCode)
{
    global $conn;

    try {
        $key = $secretQrOneCode["secret"];

        if ($status["two_factor_auth"] == 0) {
            $sql_update = "UPDATE customers SET two_factor_auth = 1 WHERE username = :username";
            $stmt = $conn->prepare($sql_update);
            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
        }

        if ($status["auth_key"] != null) {
            $sql_update = "UPDATE two_factor_authentification 
            SET auth_key = :auth_key WHERE id_customer = 
            (SELECT id_customer FROM customers WHERE username = :username)";

            $stmt = $conn->prepare($sql_update);
            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $stmt->bindValue(':auth_key', $key, PDO::PARAM_STR);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            return $secretQrOneCode["qrCodeUrl"];
        } else {

            $sql_insert = "INSERT INTO two_factor_authentification (id_customer, auth_key) VALUES ((SELECT id_customer FROM customers WHERE username = :username), :auth_key)";

            $stmt = $conn->prepare($sql_insert);
            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':auth_key', $key, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
            return $secretQrOneCode["qrCodeUrl"];
        }
    } catch (Exception $e) {
        echo "Fehler: " . $e->getMessage();
        return null;
    }
}

function deactivate2FA($username)
{
    global $conn;

    try {
        $sql = "UPDATE customers SET two_factor_auth = 0 WHERE username = :username";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . implode(":", $conn->errorInfo()));
        }

        // Bind the parameter
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . implode(":", $stmt->errorInfo()));
        }

        return true;
    } catch (Exception $e) {
        echo "Fehler: " . $e->getMessage();
        return false;
    }
}
