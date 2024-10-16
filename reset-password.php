<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
    header("location: /welcome.php");
    exit;
}

if (!defined('MY_APP')) {
    define('MY_APP', true);
}

$title = "Reset Password";


$username = $username_err = "";

?>

<?php include("main_layout/header.php"); ?>

<div class="row mt-5 mb-5" style="color: #000000A6;">
    <div class="mx-auto" style="width: 30%; min-width:300px;">
        <h2 class="d-flex justify-content-center align-items-center" style="text-transform: none;;">Reset your password</h2>
        <br>
        <div class="d-flex justify-content-center align-items-center">We will send you an email to reset your password.</a></div>
        <br><br>

        <form id="login_form" method="post" action="php_functions/resetPasswordFunctions">
            <div class="form-group">
                <input placeholder="Username" id="username" type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span id="username_err" class="text-danger"></span>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <br>
            <div class="form-group d-flex align-items-center justify-content-center">
                <button id="btn-reset-password" type="button" class="btn btn-dark" style="margin-right:20px;" onclick="validateResettingPasswordForm()">Submit</button>
                <div style="margin-right:20px;"> or </div>
                <a href="login.php" style="color:#000000A6; margin-right:30px;">Cancel</a>
            </div>
            <br>
            <input type="hidden" name="screen_width" id="screen_width_input">
            <input type="hidden" name="screen_height" id="screen_height_input">
        </form>
    </div>
</div>

<?php if (isset($_SESSION['reset_password_attempts']) && $_SESSION['reset_password_attempts'] >= 5 && time() - $_SESSION['last_attempt_time_reset_password'] < 6000) {    // 1 minutes = 60
    $extraJsFunctions = "$(document).ready(function(){
    let passwordResetModal = new bootstrap.Modal(document.getElementById('passwordResetModal'), {
        backdrop: 'static', // Disables closing on clicking outside the modal
        keyboard: false // Disables closing with the keyboard
    });

    // Show the modal
    passwordResetModal.show();
});";

?>


    <div class="modal fade" id="passwordResetModal" tabindex="-1" role="dialog" aria-labelledby="passwordResetModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordResetModalLongTitle">Too many tries.</h5>
                </div>
                <div class="modal-body">
                    <p>Too many password reset attempts. Please try again in an hour.</p>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <a class="btn btn-danger" href="mailto::yourMail@yourMail.de">
                        Contact Support
                    </a>
                    <a href="products.php" class="btn btn-secondary">Back To Products</a>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

<?php include("main_layout/footer.php"); ?>