<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false || !isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("location: /login.php");
    exit;
}

if (!defined('MY_APP')) {
    define('MY_APP', true);
}

$title = "Change Password";

?>

<?php include("main_layout/header.php"); ?>

<?php
if (!empty($login_err)) {
    echo '<div class="alert alert-danger">' . $login_err . '</div>';
}
?>

<div class="row mt-5 mb-5" style="color: #000000A6;">

    <h2 class="d-flex justify-content-center align-items-center mb-5 text-center" style="text-transform: none;">Enter your new password.</h2>
    <br><br><br>

    <div class="mx-auto" style="width: 30%; min-width:300px;">
        <form id="login_form" action="php_functions/changePasswordFunctions.php" method="post" onsubmit="return false;">
            <div class="form-group">
                <label for="password">Password</label>
                <input placeholder="Enter password" id="password" type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span id="password_err" class="text-danger"></span>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <br>
            <div class="form-group mb-4">
                <label for="confirm_password">Confirm Password</label>
                <input placeholder="Enter confirm password" id="confirm_password" type="password" name="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span id="confirm_password_err" class="text-danger"></span>
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <br>
            <div class="form-group d-flex justify-content-center align-items-center">
                <button id="btn-change-password" type="button" class="btn btn-dark mr-3" onclick="validateChangePassword()">Change Password</button>
            </div>
        </form>
    </div>
</div>


<?php include("main_layout/footer.php"); ?>
