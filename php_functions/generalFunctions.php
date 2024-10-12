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
function generatePassword()
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $length = 9;
    $password = '';

    $password .= $chars[rand(26, 51)]; // Uppercase letter
    $password .= $chars[rand(0, 25)];  // Lowercase letter
    $password .= $chars[rand(52, 61)]; // Number

    // Add remaining characters randomly
    for ($i = 0; $i < $length - 3; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }

    // Shuffle the characters in the password
    $password = str_shuffle($password);

    return $password;
}


