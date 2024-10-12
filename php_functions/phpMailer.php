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

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/vendor/phpmailer/phpmailer/src/Exception.php";
require_once $path . "/vendor/phpmailer/phpmailer/src/PHPMailer.php";
require_once $path . "/vendor/phpmailer/phpmailer/src/SMTP.php";



function sendMail($destination_email, $first_name, $last_name, $generated_password, $new_account)
{
  $mailhost = getenv('MAILHOST');
  $username = getenv('MAIL_USERNAME');
  $password = getenv('MAIL_PASSWORD');
  $sendFrom = getenv('SEND_FROM');
  $sendFromName = getenv('SEND_FROM_NAME');
  $replyTo = getenv('SEND_FROM');
  $replyToName = getenv('SEND_FROM_NAME');

  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->SMTPAuth = true;

  $mail->Host = $mailhost;
  $mail->Username = $username;
  $mail->Password = $password;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';

  $mail->setFrom($sendFrom, $sendFromName);

  // $ascii_destination_email = idn_to_ascii($destination_email, 0, INTL_IDNA_VARIANT_UTS46);
  // Changed the following in php.ini: ;extension=intl to extension=intl
  // $mail->addAddress($ascii_destination_email);
  $mail->addAddress($destination_email);

  $mail->addReplyTo($replyTo, $replyToName);
  $mail->IsHTML(true);

  //embedded images
  $mail->AddEmbeddedImage('../email_template/rsc/facebook.png', 'facebook');
  $mail->AddEmbeddedImage('../email_template/rsc/instagram.png', 'instagram');
  $mail->AddEmbeddedImage('../email_template/rsc/linkedin.png', 'linkedin');
  $mail->AddEmbeddedImage('../email_template/rsc/twitter.png', 'twitter');

  if ($new_account == true) {
    $mail->AddEmbeddedImage('../rsc/welcome/1711362877470-homepagefashiononedesktopjpg_3240x5760.webp', 'home');

    $mail->Subject = "Welcome to our Styleshop";

    $html_content = file_get_contents('../email_template/welcome_template.html');

    $html_content = str_replace('{{FIRST_NAME}}', $first_name, $html_content);
    $html_content = str_replace('{{PASSWORD}}', $generated_password, $html_content);
    $html_content = str_replace('{{MAIL}}', $sendFrom, $html_content);
    $mail->Body = $html_content;
  } else {
    $mail->AddEmbeddedImage('../rsc/welcome/1691050806675-hpcorpoone2880x1260v21jpg_1260x2880.webp', 'home-2');

    $mail->AddEmbeddedImage('../rsc/clothes/BUTTONS OVERSIZED - Sweatshirt 1.webp', 'buttons-oversized-men');
    $mail->AddEmbeddedImage('../rsc/clothes/SLIM TAPER LO BALL - Jeans Tapered Fit 1.webp', 'slim-taper-lo-ball-men');
    $mail->AddEmbeddedImage('../rsc/clothes/EMPORIO ARMANI BUTTON-DOWN BLOUSE 1.webp', 'emporio-armani-button-down-women');
    $mail->AddEmbeddedImage('../rsc/clothes/EMPORIO ARMANI GIACCA - BLAZER 1.webp', 'emporio-armani-giacca-women');

    $mail->Subject = "Styleshop - Password Recovery";

    $html_content = file_get_contents('../email_template/password_recovery_template.html');

    $html_content = str_replace('{{FIRST_NAME}}', $first_name, $html_content);
    $html_content = str_replace('{{PASSWORD}}', $generated_password, $html_content);
    $html_content = str_replace('{{MAIL}}', $sendFrom, $html_content);
    $mail->Body = $html_content;
  }


  if (!$mail->send()) {
    echo json_encode(["error" => "Email not sent. Please try again"]); // Return JSON error response
  }
}

function sendMailCheckoutInformation($destination_email, $first_name, $last_name, $cart_items, $invoice_number, $shipping_company, $shipping_price,  $totalAmountAfterSale, $reward_points, $salesForPromoCodes)
{
  $mailhost = getenv('MAILHOST');
  $username = getenv('MAIL_USERNAME');
  $password = getenv('MAIL_PASSWORD');
  $sendFrom = getenv('SEND_FROM');
  $sendFromName = getenv('SEND_FROM_NAME');
  $replyTo = getenv('SEND_FROM');
  $replyToName = getenv('SEND_FROM_NAME');

  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->SMTPAuth = true;

  $mail->Host = $mailhost;
  $mail->Username = $username;
  $mail->Password = $password;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';

  $mail->setFrom($sendFrom, $sendFromName);

  // $ascii_destination_email = idn_to_ascii($destination_email, 0, INTL_IDNA_VARIANT_UTS46);
  // Changed the following in php.ini: ;extension=intl to extension=intl
  // $mail->addAddress($ascii_destination_email);
  $mail->addAddress($destination_email);

  $mail->addReplyTo($replyTo, $replyToName);
  $mail->IsHTML(true);

  $rewardPointsSale = sprintf('%.2f', (($reward_points - ($reward_points % 100)) / 100 * 0.1));
  $salesForPromoCodes = sprintf('%.2f', $salesForPromoCodes);

  $mail->AddEmbeddedImage('../rsc/welcome/1710432541893-314ravdesktopjpg_1260x2880.webp', 'home');
  $mail->AddEmbeddedImage('../email_template/rsc/facebook.png', 'facebook');
  $mail->AddEmbeddedImage('../email_template/rsc/instagram.png', 'instagram');
  $mail->AddEmbeddedImage('../email_template/rsc/linkedin.png', 'linkedin');
  $mail->AddEmbeddedImage('../email_template/rsc/twitter.png', 'twitter');
  $mail->AddEmbeddedImage('../email_template/rsc/grey.png', 'grey-line');

  $mail->Subject = "Your ordered products will arrive soon!";

  $html_content = file_get_contents('../email_template/new_checkout_template.html');

  $html_content = str_replace('{{DATEOFORDER}}', date("F d, Y"), $html_content);
  $html_content = str_replace('{{INVOICE_NUMBER}}', $invoice_number, $html_content);
  $cart_items_input = "";
  $totalAmount = 0.00;
  $subtotal = 0.00;

  $allItemsAmount = 0;

  $all_products_html = "";

  foreach ($cart_items as $cart_item) {
    $product_name = $cart_item->getProductName();
    $cleaned_product_name = preg_replace('/[^a-zA-Z0-9]/', '', $product_name);
    $image = $cleaned_product_name;

    // $image = $cart_item->getProductName() . '-image';
    $mail->AddEmbeddedImage('..' . $cart_item->getPath(), $image);

    $totalAmount = $totalAmount + ($cart_item->getTotalAmountAfterSale());
    $subtotal = $subtotal + $cart_item->getTotalAmount();
    $allItemsAmount = $allItemsAmount + $cart_item->getAmount();

    $productName = $cart_item->getProductName();
    $item_number = $cart_item->getItemNumber();
    $amount = $cart_item->getAmount();
    $obj_subtotal =  sprintf('%.2f', ($cart_item->getPrice() * $cart_item->getAmount()));

    $new_html_content_product = file_get_contents('../email_template/product_template.html');
    $new_html_content_product = str_replace('{{PRODUCT_IMAGE}}', $image, $new_html_content_product);
    $new_html_content_product = str_replace('{{PRODUCT_NAME}}', $productName, $new_html_content_product);
    $new_html_content_product = str_replace('{{ITEM_NUMBER}}', $item_number, $new_html_content_product);
    $new_html_content_product = str_replace('{{PRODUCT_AMOUNT}}', $amount, $new_html_content_product);
    $new_html_content_product = str_replace('{{PRODUCT_PRICE}}', $obj_subtotal, $new_html_content_product);
    $new_html_content_product = str_replace('{{LINE}}', 'grey-line', $new_html_content_product);

    $all_products_html .= $new_html_content_product;
  }

  $html_content = str_replace('{{CART_ITEMS}}', $all_products_html, $html_content);

  $totalAmountAfterSale = $totalAmountAfterSale + $shipping_price;
  $totalAmountAfterSale = $totalAmountAfterSale - $salesForPromoCodes;
  $subtotal = $subtotal + $shipping_price;

  $html_content = str_replace('{{SHIPPING_COMPANY}}', $shipping_company, $html_content);
  $html_content = str_replace('{{SHIPPING_PRICE}}', sprintf('%.2f', $shipping_price), $html_content);
  $html_content = str_replace('{{SUBTOTAL}}', sprintf('%.2f', (sprintf('%.2f', $subtotal) - sprintf('%.2f', $shipping_price))), $html_content);

  $html_content = str_replace('{{QUANTITY_DISCOUNT}}', sprintf('%.2f', ($totalAmountAfterSale - $subtotal + $rewardPointsSale + $salesForPromoCodes)), $html_content);
  $html_content = str_replace('{{REWARD_POINTS_SALE}}', sprintf('%.2f', -$rewardPointsSale), $html_content);
  $html_content = str_replace('{{SALES_FOR_PROMO_CODES}}', sprintf('%.2f', -$salesForPromoCodes), $html_content);
  $html_content = str_replace('{{ALL_ITEMS_AMOUNT}}', $allItemsAmount, $html_content);
  $html_content = str_replace('{{TOTAL_AMOUNT_AFTER_SALE}}', sprintf('%.2f', ($totalAmountAfterSale)), $html_content);
  $html_content = str_replace('{{LINE}}', 'grey-line', $html_content);
  $html_content = str_replace('{{CART_ITEMS}}', $cart_items_input, $html_content);

  $html_content = str_replace('{{FIRST_NAME}}', $first_name, $html_content);
  $html_content = str_replace('{{MAIL}}', $sendFrom, $html_content);


  $mail->Body = $html_content;

  if (!$mail->send()) {
    echo json_encode(["error" => "Email not sent. Please try again"]); // Return JSON error response
  }
}
