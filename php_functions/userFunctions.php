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


function getUserMainData($username)
{
    global $conn;

    $sql_userdata = "SELECT 
            id_customer, 
            username, 
            first_name, 
            last_name, 
            street, 
            postal_code, 
            location,
            country,
            gender
        FROM customers
        WHERE username = :username";

    try {
        if ($stmt_select = $conn->prepare($sql_userdata)) {
            $stmt_select->bindValue(':username', $username, PDO::PARAM_STR);

            if ($stmt_select->execute()) {
                $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

                if (count($results) >= 1) {
                    $userData = array(
                        "id_customer" => $results[0]['id_customer'],
                        "username" => $results[0]['username'],
                        "first_name" => $results[0]['first_name'],
                        "last_name" => $results[0]['last_name'],
                        "street" => $results[0]['street'],
                        "postal_code" => $results[0]['postal_code'],
                        "location" => $results[0]['location'],
                        "country" => $results[0]['country'],
                        "gender" => $results[0]['gender']
                    );
                    return $userData;
                } else {
                    return null;
                }
            } else {
                throw new Exception('Oops! Something went wrong. Please try again later.');
            }
        } else {
            throw new Exception('Oops! Something went wrong. Please try again later.');
        }
    } catch (Exception $e) {
        echo json_encode(array('error' => $e->getMessage()));
        return null;
    }
}
