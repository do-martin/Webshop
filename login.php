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

$title = "Login";
$username = $password = $last_name  = $gender = $last_login = "";
$username_err = $password_err = $login_err = "";
$user_current_os = $user_current_screen_res = "";

if (isset($_GET["login_err"])) {
    $password_err = "Invalid username or password.";
}

$extraJsFunctions = "";

?>

<?php include("main_layout/header.php"); ?>

<div class="row mt-5 mb-5" style="color: #000000A6;">

    <h2 class="d-flex justify-content-center align-items-center" style="text-transform: none;">Login</h2>
    <br>
    <div class="d-flex justify-content-center align-items-center">
        <div style="margin-right: 10px;">Don't have an account?</div><a href="register.php" style="color:#000000A6;">Sign up here.</a>
    </div>
    <br><br><br>

    <div class="mx-auto" style="width: 30%; min-width:300px;">
        <form id="login_form" action="php_functions/loginFunctions.php" method="post">
            <div class="form-group mb-4">
                <label for="username">Username</label>
                <input placeholder="Enter username" id="username" type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span id="username_err" class="text-danger"></span>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <br>
            <div class="form-group">
                <label for="password">Password</label>
                <input placeholder="Enter password" id="password" type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span id="password_err" class="text-danger"></span>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                <?php if (!empty($login_err)) echo '<span class="text-danger">' . htmlspecialchars($login_err) . '</span>'; ?>
            </div>
            <br>
            <div class="form-group d-flex justify-content-between align-items-center">
                <button id="btn-login" type="button" class="btn btn-dark mr-3" onclick="validateLoginForm()">Sign In</button>
                <a href="reset-password.php" style="color:#000000A6;">Forgot your password?</a>
            </div>
            <br>
            <input type="hidden" name="screen_width" id="screen_width_input">
            <input type="hidden" name="screen_height" id="screen_height_input">
            <input type="hidden" id="secret" name="secret">
        </form>
    </div>
</div>

<div class="modal fade" id="twofaModal" tabindex="-1" role="dialog" aria-labelledby="twofaModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="twofaModalLongTitle">Two Factor Authentification</h5>
            </div>
            <form id="form-2fa" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="mb-2" for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter your code">
                        <span id="invalid-code" class="text-danger"></span>
                    </div>
                    <input type="hidden" class="form-control" id="os" name="os">
                </div>
            </form>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-light" data-dismiss="modal" onclick="closeModal()">Close</button>
                <button type="button" class="btn btn-secondary" onclick="validateCodeAndSubmit()">Continue Login</button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5 && time() - $_SESSION['last_attempt_time_login'] < 60) {    // 1 minutes = 60
    $extraJsFunctions = "$(document).ready(function(){
    let loginFailureModal = new bootstrap.Modal(document.getElementById('loginFailureModal'), {
        backdrop: 'static', // Disables closing on clicking outside the modal
        keyboard: false // Disables closing with the keyboard
    });

    // Show the modal
    loginFailureModal.show();
});";

?>


    <div class="modal fade" id="loginFailureModal" tabindex="-1" role="dialog" aria-labelledby="loginFailureModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginFailureModalLongTitle">Login failed!</h5>
                </div>
                <div class="modal-body">
                    <p>Too many attempts. Please try again later in a couple of minutes.</p>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <a class="btn btn-danger" href="mailto:martin.do@student.reutlingen-university.de">
                        Contact Support
                    </a>
                    <a href="products.php" class="btn btn-secondary">Back To Products</a>
                </div>
            </div>
        </div>
    </div>

<?php } ?>



<?php include("main_layout/footer.php"); ?>