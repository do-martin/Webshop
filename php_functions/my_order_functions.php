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

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/models/invoiceModel.php";
require_once $path . "/models/productModel.php";
require_once $path . "/models/cartModel.php";

function getAllOrders($username, $year = null)
{
    if ($year == null) {
        $year = date("Y");
    }
    global $conn;
    $param_username = $id_invoice_number = $order_date = $shipping_company = $total_amount = $ordered_articles = $amount_ordered_articles = $product_names = $prices = $item_inventories = $path_imgs = $categories = $genders = "";
    $subtotal = $sales_promo_code = 0.00;
    $shipping_price = $used_points = 0;

    // $sql_get_orders = "SELECT 
    //                     ih.id_invoice_number, 
    //                     ih.order_date, 
    //                     ih.shipping_company, 
    //                     ih.total_amount,
    //                     ih.shipping_price,
    //                     ih.used_points,
    //                     ih.subtotal,
    //                     ih.sales_promo_code,
    //                 GROUP_CONCAT(ip.id_item_num SEPARATOR ', ') AS ordered_articles,
    //                 GROUP_CONCAT(ip.amount SEPARATOR ', ') AS amount_ordered_articles,
    //                 GROUP_CONCAT(p.prod_name SEPARATOR ', ') AS product_names,
    //                 GROUP_CONCAT(p.price SEPARATOR ', ') AS prices,
    //                 GROUP_CONCAT(p.item_inventory SEPARATOR ', ') AS item_inventories,
    //                 GROUP_CONCAT(p.path_img SEPARATOR ', ') AS path_imgs,
    //                 GROUP_CONCAT(p.category SEPARATOR ', ') AS categories,
    //                 GROUP_CONCAT(p.gender SEPARATOR ', ') AS genders
    //                 FROM 
    //                     invoice_header AS ih
    //                 JOIN 
    //                     invoice_position AS ip ON ih.id_invoice_number = ip.id_invoice_number
    //                     JOIN
    //                 products AS p ON ip.id_item_num = p.id_item_num
    //                 WHERE 
    //                     ih.id_customer = (SELECT id_customer FROM customers WHERE username = :username)
    //                     AND YEAR(ih.order_date) = :year
    //                 GROUP BY 
    //                     ih.id_invoice_number, 
    //                     ih.order_date, 
    //                     ih.shipping_company, 
    //                     ih.total_amount
    //                 ORDER BY ih.order_date DESC
    //                     ";

    $sql_get_orders = "SELECT 
    ih.id_invoice_number, 
    ih.order_date, 
    ih.shipping_company, 
    ih.total_amount,
    ih.shipping_price,
    ih.used_points,
    ih.subtotal,
    ih.sales_promo_code,
    STRING_AGG(ip.id_item_num, ', ') AS ordered_articles,
    STRING_AGG(ip.amount, ', ') AS amount_ordered_articles,
    STRING_AGG(p.prod_name, ', ') AS product_names,
    STRING_AGG(p.price, ', ') AS prices,
    STRING_AGG(p.item_inventory, ', ') AS item_inventories,
    STRING_AGG(p.path_img, ', ') AS path_imgs,
    STRING_AGG(p.category, ', ') AS categories,
    STRING_AGG(p.gender, ', ') AS genders
FROM 
    invoice_header AS ih
JOIN 
    invoice_position AS ip ON ih.id_invoice_number = ip.id_invoice_number
JOIN
    products AS p ON ip.id_item_num = p.id_item_num
WHERE 
    ih.id_customer = (SELECT id_customer FROM customers WHERE username = :username)
    AND YEAR(ih.order_date) = :year
GROUP BY 
    ih.id_invoice_number, 
    ih.order_date, 
    ih.shipping_company, 
    ih.total_amount,
    ih.shipping_price,
    ih.used_points,
    ih.subtotal,
    ih.sales_promo_code
ORDER BY ih.order_date DESC";



    if ($stmt_select = $conn->prepare($sql_get_orders)) {
        $stmt_select->bindParam(':username', $param_username, PDO::PARAM_STR);
        $stmt_select->bindParam(':year', $year, PDO::PARAM_INT);

        $param_username = $username;

        if ($stmt_select->execute()) {
            $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
            $orders = array();

            if (count($results) >= 1) {
                foreach ($results as $result) {
                    $products = array();
                    $id_invoice_number = $result["id_invoice_number"];
                    $order_date = $result["order_date"];
                    $shipping_company = $result["shipping_company"];
                    $total_amount = $result["total_amount"];
                    $shipping_price = $result["shipping_price"];
                    $used_points = $result["used_points"];
                    $subtotal = $result["subtotal"];
                    $sales_promo_code = $result["sales_promo_code"];
                    $ordered_articles = $result["ordered_articles"];
                    $amount_ordered_articles = $result["amount_ordered_articles"];
                    $product_names = $result["product_names"];
                    $prices = $result["prices"];
                    $item_inventories = $result["item_inventories"];
                    $path_imgs = $result["path_imgs"];
                    $categories = $result["categories"];
                    $genders = $result['genders'];

                    $ordered_articles = explode(", ", $ordered_articles);
                    $amount_ordered_articles = explode(", ", $amount_ordered_articles);
                    $product_names = explode(", ", $product_names);
                    $prices = explode(", ", $prices);
                    $item_inventories = explode(", ", $item_inventories);
                    $path_imgs = explode(", ", $path_imgs);
                    $categories = explode(", ", $categories);
                    $genders = explode(", ", $genders);


                    for ($i = 0; $i < count($product_names); $i++) {
                        $product = new Cart($ordered_articles[$i], $product_names[$i], $prices[$i], $amount_ordered_articles[$i], $item_inventories[$i], $path_imgs[$i], $categories[$i], $genders[$i]);
                        array_push($products, $product);
                    }

                    $total_without_used_points_and_promo_code = sprintf("%.2f", $total_amount) + sprintf("%.2f", $used_points / 100 * 0.1) + sprintf("%.2f", $sales_promo_code);

                    $invoice = new Invoice($id_invoice_number, $order_date, $shipping_company, $total_amount, $products, $shipping_price, $used_points, $total_without_used_points_and_promo_code);

                    array_push($orders, $invoice);
                }
                // $stmt_select->bind_result($id_invoice_number, $order_date, $shipping_company, $total_amount, $shipping_price, $used_points, $subtotal, $sales_promo_code, $ordered_articles, $amount_ordered_articles, $product_names, $prices, $item_inventories, $path_imgs, $categories, $genders);
                // $orders = array();

                // while ($stmt_select->fetch()) {
                // $products = array();
                // $ordered_articles = explode(", ", $ordered_articles);
                // $amount_ordered_articles = explode(", ", $amount_ordered_articles);
                // $product_names = explode(", ", $product_names);
                // $prices = explode(", ", $prices);
                // $item_inventories = explode(", ", $item_inventories);
                // $path_imgs = explode(", ", $path_imgs);
                // $categories = explode(", ", $categories);
                // $genders = explode(", ", $genders);

                // for ($i = 0; $i < count($product_names); $i++) {
                //     $product = new Cart($ordered_articles[$i], $product_names[$i], $prices[$i], $amount_ordered_articles[$i], $item_inventories[$i], $path_imgs[$i], $categories[$i], $genders[$i]);
                //     array_push($products, $product);
                // }

                // $total_without_used_points_and_promo_code = sprintf("%.2f", $total_amount) + sprintf("%.2f", $used_points / 100 * 0.1) + sprintf("%.2f", $sales_promo_code);

                // $invoice = new Invoice($id_invoice_number, $order_date, $shipping_company, $total_amount, $products, $shipping_price, $used_points, $total_without_used_points_and_promo_code);

                // array_push($orders, $invoice);
                // }
                // $stmt_select->close();
                return $orders;
            }
        }
    }
}
