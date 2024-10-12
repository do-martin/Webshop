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

function getProductData($gender = null, $limit = null)
{
    global $conn;
    $product_list = [];

    $sql = "SELECT 
                id_item_num,
                prod_name,
                price,
                item_inventory,
                path_img,
                category,
                gender
            FROM 
                products";
    if ($gender != null) {
        $sql .= " WHERE gender = :gender";
    }
    if ($limit != null) {
        $sql .= " ORDER BY NEWID() OFFSET 0 ROWS FETCH NEXT :limit ROWS ONLY";
    }
    if ($stmt = $conn->prepare($sql)) {
        if ($gender != null && $limit != null) {
            $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
            $stmt->bindValue(':limit', 4, PDO::PARAM_INT);
        } else if ($gender != null) {
            $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
        } else if ($limit != null) {
            $stmt->bindValue(':limit', 4, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $product = new Product(
                    $row['id_item_num'],
                    $row['prod_name'],
                    $row['price'],
                    $row['item_inventory'],
                    $row['path_img'],
                    $row['category'],
                    $row['gender']
                );
                $product_list[] = $product;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    } else {
        echo "Oops! Could not prepare the statement. Please try again later.";
    }
    return $product_list;
}
