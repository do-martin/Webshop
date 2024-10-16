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

$title = "Register";

$username = $first_name = $last_name = $address = $postal_code = $location = $country = "";
$username_err = $first_name_err = $last_name_err = $address_err = $postal_code_err = $location_err = $country_err = "";
$screen_height = $screen_width = 0;

$extraJsFunctions = "$(document).ready(function() {
    loadAvailableUsernames();
    setInterval(loadAvailableUsernames, 1000);
});";

?>

<?php include("main_layout/header.php"); ?>

<div class="row mt-5 mb-5" style="color: #000000A6;">
    <div class="mx-auto" style="width: 30%; min-width:300px;">
        <h2 class="d-flex justify-content-center align-items-center" style="text-transform: none;">Create Account</h2><br>
        <div class="d-flex justify-content-center align-items-center mb-5">
            <div style="margin-right: 10px;">Already have an account?</div><a href="login.php" style="color:#000000A6;">Sign in here.</a>
        </div>

        <form id="form_send_valid_data_to_server" action="/php_functions/registerFunction.php" method="post" onsubmit="return false;">
            <div class="form-group mb-4">
                <label for="username">Username</label>
                <input type="email" name="username" id="username" placeholder="Enter email" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span id="username_err" class="text-danger"><?php echo $username_err; ?></span>

            </div>
            <div class="form-group mb-4">
                <label for="first_name">First name</label>
                <input placeholder="Enter first name" type="text" id="first_name" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                <span id="first_name_err" class="text-danger"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group mb-4">
                <label for="last_name">Last name</label>
                <input placeholder="Enter last name" type="text" id="last_name" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                <span id="last_name_err" class="text-danger"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group mb-4">
                <label for="address">Address</label>
                <input placeholder="Enter address" type="text" id="address" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                <span id="address_err" class="text-danger"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group mb-4">
                <label for="postal_code">Postal code</label>
                <input placeholder="Enter postal code" type="text" id="postal_code" name="postal_code" class="form-control <?php echo (!empty($postal_code_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $postal_code; ?>">
                <span id="postal_code_err" class="text-danger"><?php echo $postal_code_err; ?></span>
            </div>
            <div class="form-group mb-4">
                <label for="location">City</label>
                <input placeholder="Enter City" type="text" id="location" name="location" class="form-control <?php echo (!empty($location_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $location; ?>">
                <span id="location_err" class="text-danger"><?php echo $location_err; ?></span>
            </div>
            <div class="form-group mb-4">
                <label for="country">Country</label>
                <input placeholder="Enter country" type="text" id="country" name="country" class="form-control <?php echo (!empty($country_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $country; ?>">
                <span id="country_err" class="text-danger"><?php echo $country_err; ?></span>
            </div>

            <div class="form-group mb-4">
                <input id="btn-registry" type="button" class="btn btn-dark" value="Create" onclick="validateAndSubmitFormRegistry()">
            </div>

            <input type="hidden" name="screen_width" id="screen_width_input">
            <input type="hidden" name="screen_height" id="screen_height_input">
        </form>
    </div>
</div>

<?php if (isset($_SESSION['register_attempts']) && $_SESSION['register_attempts'] >= 10 && time() - $_SESSION['last_attempt_time_register'] < 3600) {    // 1 minutes = 60
//     $extraJsFunctions = "$(document).ready(function(){
//     let registerFailureModal = new bootstrap.Modal(document.getElementById('registerFailureModal'), {
//         backdrop: 'static', // Disables closing on clicking outside the modal
//         keyboard: false // Disables closing with the keyboard
//     });

//     // Show the modal
//     registerFailureModal.show();
// });";

?>


    <div class="modal fade" id="registerFailureModal" tabindex="-1" role="dialog" aria-labelledby="registerFailureModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerFailureModalLongTitle">Too many registrations.</h5>
                </div>
                <div class="modal-body">
                    <p>Too many registration attempts. Please try again in an hour.</p>
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