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


function checkGoogleAuthentification($input)
{
    require_once '../vendor/autoload.php';

    $ga = new PHPGangsta_GoogleAuthenticator();
    $secret = $ga->createSecret();
    echo "Secret is: " . $secret . "\n\n <br><br>";

    $qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
    echo "Google Charts URL for the QR-Code: " . $qrCodeUrl . "\n\n<br><br>";

    $oneCode = $ga->getCode($secret);
    echo "Checking Code '$oneCode' and Secret '$secret':\n<br><br>";

    $checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
    if ($checkResult) {
        echo 'OK';
    } else {
        echo 'FAILED';
    }
}

// checkGoogleAuthentification('test');