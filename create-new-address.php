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

$title = "Add new address";

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/php_functions/userFunctions.php";
require_once $path . "/php_functions/checkForFirstLogin.php";

$btnActivationState = "";

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}

?>

<?php include("main_layout/header.php"); ?>

<div class="row mt-5 mb-5" style="color: #000000A6;">
    <div class="mx-auto" style="width: 30%; min-width:300px;">
        <h2 class="d-flex justify-content-center align-items-center" style="text-transform: none;">My data</h2><br>
        <form id="form_send_valid_data_to_server" action="/php_functions/addressFunction.php" method="post" onsubmit="return false;">
            <div class="form-group mb-4">
                <label for="gender">Gender</label>
                <select required class="form-control" id="gender" name="gender">
                    <option value="m">male</option>
                    <option value="f">female</option>
                    <option value="d">diverse</option>
                </select>
                <span id="gender_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="first_name">First name</label>
                <input required placeholder="First name" type="text" id="first_name" name="first_name" class="form-control">
                <span id="first_name_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="last_name">Last name</label>
                <input required placeholder="Last name" type="text" id="last_name" name="last_name" class="form-control">
                <span id="last_name_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="address">Address</label>
                <input required placeholder="Address" type="text" id="address" name="address" class="form-control">
                <span id="address_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="postal_code">Postal code</label>
                <input required placeholder="Postal code" type="text" id="postal_code" name="postal_code" class="form-control">
                <span id="postal_code_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="location">Location</label>
                <input required placeholder="Location" type="text" id="location" name="location" class="form-control">
                <span id="location_err" class="text-danger"></span>
            </div>
            <div class="form-group mb-4">
                <label for="country">Country</label>
                <input required placeholder="Country" type="text" id="country" name="country" class="form-control">
                <span id="country_err" class="text-danger"></span>
            </div>

            <input type="hidden" name="action_token" value="createAddress">

            <div class="form-group mb-4 d-flex justify-content-between">
                <input id="btn-save-address" type="button" class="btn btn-dark" value="Save Address" onclick="validateAndSubmitFormAddress()">
            </div>
        </form>
    </div>
</div>


<?php include("main_layout/footer.php"); ?>