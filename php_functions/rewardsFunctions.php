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

function getRewardPoints($username)
{
    global $conn;
    $param_username = "";
    $rewardPoints = "";
    $rewardPoints_db = "";

    $sql_get_reward_points = "SELECT points FROM reward_points WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username)";

    if ($stmt_select = $conn->prepare($sql_get_reward_points)) {
        $param_username = trim($username);
        $sanitized_email = filter_var($param_username, FILTER_SANITIZE_EMAIL);
        if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
            $param_username = $sanitized_email;
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
            exit();
        }
        $stmt_select->bindParam(':username', $param_username, PDO::PARAM_STR);

        if ($stmt_select->execute()) {
            $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) >= 1) {
                $rewardPoints = $results[0]['points'];
                return $rewardPoints;
            } else {
                echo json_encode(array('error' => 'No user data found.'));
            }
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
}

function getCustomerRewardPoints($username)
{
    global $conn;
    $param_username = "";
    $reward_points = 0;
    $modulo = 0;

    // $sql_get_points_and_modulo = "SELECT r.points, 
    // (SELECT(r.points % 100) as modulo)
    // FROM reward_points as r 
    // WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username)";

    $sql_get_points_and_modulo = "SELECT 
    r.points, 
    r.points % 100 AS modulo
FROM 
    reward_points AS r
WHERE 
    r.id_customer = (SELECT id_customer FROM customers WHERE username = :username)";

    if ($stmt_select = $conn->prepare($sql_get_points_and_modulo)) {
        $param_username = $username;
        $sanitized_email = filter_var($param_username, FILTER_SANITIZE_EMAIL);
        if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
            $param_username = $sanitized_email;
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
            exit();
        }
        $stmt_select->bindParam(':username', $param_username, PDO::PARAM_STR);

        if ($stmt_select->execute()) {
            $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
            $reward_points = $results[0]['points'];
            $modulo = $results[0]['modulo'];
            return ["success" => true, "reward_points" => $reward_points, "modulo" => $modulo];
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
    return ["success" => false];
}

function useRewardPointsAndDecrease($username, $points)
{
    global $conn;
    $param_username = $param_points = "";

    $sql_use_points = "UPDATE reward_points SET points = points - :points WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username)";
    if ($stmt_update = $conn->prepare($sql_use_points)) {
        $sanitized_email = filter_var($username, FILTER_SANITIZE_EMAIL);
        if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
            $param_username = $sanitized_email;
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
            exit();
        }

        $stmt_update->bindParam(':points', $points, PDO::PARAM_INT);
        $stmt_update->bindParam(':username', $param_username, PDO::PARAM_STR);

        if ($stmt_update->execute()) {
            return ["success" => true];
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
        }
    } else {
        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
    }
    return ["success" => false];
}

function updateRewardPointsSQL($username, $points)
{
    global $conn;
    $param_username = "";
    $id_reward_db = "";
    $id_reward = "";

    $sql_get_id_reward = "SELECT id_reward FROM reward_points 
        WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username)";

    if ($stmt_select = $conn->prepare($sql_get_id_reward)) {
        $sanitized_email = filter_var($username, FILTER_SANITIZE_EMAIL);
        if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
            $param_username = $sanitized_email;
        } else {
            echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
            exit();
        }
        $stmt_select->bindValue(':username', $param_username, PDO::PARAM_STR);

        if ($stmt_select->execute()) {
            $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) >= 1) {
                // $stmt_select->bind_result($id_reward_db);
                $id_reward = $results[0]['id_reward'];


                $sql_update_reward = "UPDATE reward_points SET points = points + " . $points . " WHERE id_customer = (SELECT id_customer FROM customers WHERE username = :username)";
                if ($stmt_update = $conn->prepare($sql_update_reward)) {
                    $stmt_update->bindValue(':username', $param_username, PDO::PARAM_STR);
                    $param_username = $username;

                    if ($stmt_update->execute()) {
                        return true;
                    } else {
                        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                    }
                } else {
                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                }
            } else {
                $sql_create_reward = "INSERT INTO reward_points(id_customer, points)
                    VALUES((SELECT id_customer FROM customers WHERE username = :username), " . $points . ")";

                if ($stmt_insert = $conn->prepare($sql_create_reward)) {
                    // $stmt_insert->bind_param("s", $param_username);
                    $stmt_insert->bindValue(':username', $param_username, PDO::PARAM_STR);
                    $param_username = $username;

                    if ($stmt_insert->execute()) {
                        return true;
                    } else {
                        echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                    }
                } else {
                    echo json_encode(array('error' => 'Oops! Something went wrong. Please try again later.'));
                }
            }
        }
    }
}
