<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false || !isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("location: /login.php");
    exit;
}

if (!defined('MY_APP')) {
    define('MY_APP', true);
}

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/php_functions/userFunctions.php";
require_once $path . "/php_functions/generalFunctions.php";

$title = "Logout";

date_default_timezone_set('Europe/Berlin');
$current_time = date("Y-m-d H:i:s");
$user_current_screen_res = $_GET["screen_width"] . ' x ' . $_GET["screen_height"];

$screenWidth = $_GET["screen_width"];
$screenHeight = $_GET["screen_height"];
$username = $_SESSION["username"];

date_default_timezone_set('Europe/Berlin');
$current_time = date("Y-m-d H:i:s");
$user_current_os = $_GET['os'];
$user_current_screen_res = $screenWidth . 'x' . $screenHeight;
$sql_update = "UPDATE l
SET 
    l.last_login = :current_time, 
    l.activity = 0, 
    l.last_screen_res = :user_current_screen_res, 
    l.last_op_system = :user_current_os
FROM logs AS l
JOIN customers AS c ON c.id_customer = l.id_customer
WHERE c.username = :username
";

if ($stmt = $conn->prepare($sql_update)) {

    $param_current_time = $current_time;
    $param_user_current_screen_res = $user_current_screen_res;
    $param_user_current_os = $user_current_os;
    $param_username = $username;

    $stmt->bindValue(":current_time", $param_current_time, PDO::PARAM_STR);
    $stmt->bindValue(":user_current_screen_res", $param_user_current_screen_res, PDO::PARAM_STR);
    $stmt->bindValue(":user_current_os", $param_user_current_os, PDO::PARAM_STR);
    $stmt->bindValue(":username", $param_username, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // echo "Logout successful.";
    } else {
        echo "Something went wrong.";
    }

} else {
    echo "Error: Unable to prepare statement.";
}


session_destroy();

header("location: /");
