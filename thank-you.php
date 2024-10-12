<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false || !isset($_GET['last_id'])) {
    header("location: /login.php");
    exit;
}

if (!defined('MY_APP')) {
    define('MY_APP', true);
}

$title = "Thank You";

$extraJsFunctions = "
function applyStyles() {
    document.body.style.backgroundColor = '#f8f9fa';
    document.body.style.fontFamily = \"'Segoe UI', Tahoma, Geneva, Verdana, sans-serif\";
    document.querySelector('.container').style.marginTop = '50px';
    document.querySelector('.thank-you-text').style.fontSize = '24px';
    document.querySelector('.thank-you-text').style.color = '#28a745';
    document.querySelector('.order-details').style.marginTop = '20px';
    document.querySelector('.order-details').style.fontSize = '18px';
}
applyStyles();
";


?>

<?php include("main_layout/header.php"); ?>
<div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="mb-4">Thank You for Your Order!</h1>
                <p class="thank-you-text">We're delighted you've chosen our clothing.</p>
                <p class="order-details">Your order number is: <strong>#<?php echo $_GET['last_id']; ?></strong></p>
                <p class="order-details">A confirmation email has been sent to your email address.</p>
                <a href="index.php" class="btn btn btn-dark mr-3">Back to Homepage</a>
            </div>
        </div>
    </div>
</div>

<?php include("main_layout/footer.php"); ?>