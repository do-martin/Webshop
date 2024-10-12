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

$title = "My data";

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/php_functions/userFunctions.php";
require_once $path . "/php_functions/twoFactorAuthFunctions.php";
require_once $path . "/php_functions/checkForFirstLogin.php";

$btnActivationState = "";

$extraJsFunctions = "function setBtnInProgress(){
    document.getElementById('btn-my-data-save-changes').disabled = true;
    document.getElementById('btn-my-data-save-changes').value = 'Processing...';
    }";

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $customer = getUserMainData($username);
    $TwoFAState = getUserTwoFAStatus($username);

    if ($TwoFAState["two_factor_auth"] == 0 || $TwoFAState["auth_key"] == null) {
        $btnActivationState = "2FA Deactivated";
    } else {
        $btnActivationState = "2FA Activated";
    }
}

?>

<?php include("main_layout/header.php"); ?>

<div class="row mt-5 mb-5" style="color: #000000A6;">
    <div class="mx-auto my-data-width">
        <h2 class="d-flex justify-content-center align-items-center" style="text-transform: none;">My data</h2><br>

        <form id="form_send_valid_data_to_server" onsubmit="setBtnInProgress()" action="/php_functions/myDataFunctions.php" method="post">
            <div class="form-group mb-4">
                <label for="username">Username</label>
                <input required type="email" name="username" id="username" placeholder="Username" disabled="true" class="form-control" value="<?php echo $customer["username"]; ?>">
                <span id="username_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="first_name">First name</label>
                <input required placeholder="First name" type="text" id="first_name" name="first_name" class="form-control" value="<?php echo $customer["first_name"]; ?>">
                <span id="first_name_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="last_name">Last name</label>
                <input required placeholder="Last name" type="text" id="last_name" name="last_name" class="form-control" value="<?php echo $customer["last_name"]; ?>">
                <span id="last_name_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="address">Address</label>
                <input required placeholder="Address" type="text" id="address" name="address" class="form-control" value="<?php echo $customer["street"]; ?>">
                <span id="address_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="postal_code">Postal code</label>
                <input required placeholder="Postal code" type="text" id="postal_code" name="postal_code" class="form-control" value="<?php echo $customer["postal_code"]; ?>">
                <span id="postal_code_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="location">Location</label>
                <input required placeholder="Location" type="text" id="location" name="location" class="form-control" value="<?php echo $customer["location"]; ?>">
                <span id="location_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="country">Country</label>
                <input required placeholder="Country" type="text" id="country" name="country" class="form-control" value="<?php echo $customer["country"]; ?>">
                <span id="country_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="gender">Gender</label>
                <select required class="form-control" id="gender" name="gender">
                    <option value="m">male</option>
                    <option value="f">female</option>
                    <option value="d">diverse</option>
                </select>
                <span id="gender_err" class="text-danger"></span>
            </div>

            <input type="hidden" name="action_token" value="updateMainData">

            <div class="form-group mb-4 d-flex justify-content-between">
                <input id="btn-my-data-save-changes" type="submit" class="btn btn-dark  btn-my-data" value="Save Changes">
                <button id="btn-activation-2fa" type="button" class="btn  btn-my-data
            <?php if ($btnActivationState == "2FA Activated") {
                echo "btn-success";
            } else {
                echo "btn-danger";
            } ?> mr-2" style="width: 200px;" onclick="activate2FA()"><?php echo $btnActivationState; ?></button>
            </div>
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
                <div id="setupForm" class="mt-4 w-100 mb-5">
                    <div id="qrCode-window" class="w-100 d-flex justify-content-center align-items-center mt-5 mb-5">
                    </div>
                </div>
            </form>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-success btn-my-data" onclick="setupNew2FA()">Setup New 2FA</button>
                <button type="button" class="btn btn-secondary btn-my-data" data-dismiss="modal" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include("main_layout/footer.php"); ?>