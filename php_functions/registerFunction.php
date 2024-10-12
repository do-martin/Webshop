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

if (!isset($_SESSION['register_attempts'])) {
    $_SESSION['register_attempts'] = 0;
    $_SESSION['last_attempt_time_register'] = time();
}

if (isset($_SESSION['register_attempts']) && isset($_SESSION['last_attempt_time_register']) && time() - $_SESSION['last_attempt_time_register'] < 3600) {
    $_SESSION['register_attempts']++;
    $_SESSION['last_attempt_time_register'] = time();
}

if (isset($_SESSION['register_attempts']) && isset($_SESSION['last_attempt_time_register']) && time() - $_SESSION['last_attempt_time_register'] > 360000) {
    $_SESSION['register_attempts'] = 0;
}

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
// require_once $path . "/php_functions/userFunctions.php";
require_once $path . "/php_functions/phpMailer.php";
require_once $path . "/php_functions/generalFunctions.php";

$username = $first_name = $last_name = $address = $postal_code = $location = $country = "";
$username_err = $first_name_err = $last_name_err = $address_err = $postal_code_err = $location_err = $country_err = "";

$screen_height = $screen_width = 0;

if (isset($_GET['action']) && !empty($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'loadAvailableUsernames':
            $sql = "SELECT username FROM customers";
            if ($stmt = $conn->prepare($sql)) {
                if ($stmt->execute()) {
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $users = array();

                    foreach ($results as $row) {
                        $users[] = array(
                            'username' => htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8')
                        );
                    }

                    echo json_encode($users);
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $screen_width = $_POST["screen_width"];
    $screen_height = $_POST["screen_height"];

    if (!empty(trim($_POST["username"]))) {
        $sql = "SELECT id_customer FROM customers WHERE username = :username";
        $param_username = trim($_POST["username"]);

        if ($stmt = $conn->prepare($sql)) {

            $sanitized_email = filter_var($param_username, FILTER_SANITIZE_EMAIL);
            if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
                $param_username = $sanitized_email;
            } else {
                header("location: /login.php");
                exit();
            }

            $stmt->bindValue(':username', $sanitized_email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($results) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = $param_username;
                    $generated_password = generatePassword();
                    if (
                        empty($username_err) && empty($first_name_err) && empty($last_name_err) && empty($address_err)
                        && empty($postal_code_err) && empty($location_err) && empty($country_err)
                    ) {
                        date_default_timezone_set('Europe/Berlin');
                        $current_time = date("Y-m-d H:i:s");
                        $sql = "INSERT INTO customers (
                        username, 
                        pw, 
                        first_name, 
                        last_name,
                        street, 
                        postal_code, 
                        location, 
                        country,
                        gender)
                        VALUES (:username, :pw, :first_name, :last_name, :street, :postal_code, :location, :country, :gender)";

                        $postal_code = (int)$postal_code;

                        if ($stmt = $conn->prepare($sql)) {
                            $param_username = $sanitized_email;
                            $param_password = trim(hash('sha512', $generated_password)); // Creates a password hash
                            $param_first_name = strip_tags(trim($_POST["first_name"]));
                            $param_last_name = strip_tags(trim($_POST["last_name"]));
                            $param_address = strip_tags(trim($_POST["address"]));
                            $param_postal_code = filter_var(trim($_POST["postal_code"]), FILTER_SANITIZE_NUMBER_INT);
                            $param_location = strip_tags(trim($_POST["location"]));
                            $param_country = strip_tags(trim($_POST["country"]));
                            $param_gender = "d";

                            $stmt->bindValue(':username', $param_username, PDO::PARAM_STR);
                            $stmt->bindValue(':pw', $param_password, PDO::PARAM_STR);
                            $stmt->bindValue(':first_name', $param_first_name, PDO::PARAM_STR);
                            $stmt->bindValue(':last_name', $param_last_name, PDO::PARAM_STR);
                            $stmt->bindValue(':street', $param_address, PDO::PARAM_STR);
                            $stmt->bindValue(':postal_code', $param_postal_code, PDO::PARAM_INT);
                            $stmt->bindValue(':location', $param_location, PDO::PARAM_STR);
                            $stmt->bindValue(':country', $param_country, PDO::PARAM_STR);
                            $stmt->bindValue(':gender', $param_gender, PDO::PARAM_STR);


                            if ($stmt->execute()) {
                                sendMail($param_username, $param_first_name, $param_last_name, $generated_password, true);
                                header("location: /login.php");
                            } else {
                                echo "Oops! Something went wrong. Please try again later: " . $stmt->error;
                            }
                        }
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later: " . $stmt->error;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later: " . $conn->error;
        }
    }
}
