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

$title = "Checkout";
$subtotal = 0;
$discount = 0;
$rewardPoints = 0;

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/config/config.php";
require_once $path . "/php_functions/userFunctions.php";
require_once $path . "/php_functions/checkoutFunctions.php";
require_once $path . "/php_functions/checkForFirstLogin.php";

?>

<?php include("main_layout/header.php"); ?>


<div class="container">
    <div class="py-5 text-center">
        <h2>Checkout</h2>
    </div>
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your cart</span>
                <span class="badge badge-secondary badge-pill">3</span>
            </h4>
            <ul class="list-group mb-3">

                <?php foreach ($cart_items as $cart) { ?>
                    <li class="d-flex justify-content-between lh-condensed" style="border-radius: 0px;">
                        <div>
                            <h6 class="my-0"><?php echo htmlspecialchars($cart->getProductName()); ?>'</h6>
                            <span class="text-muted">Amount <?php echo htmlspecialchars($cart->getAmount()); ?></span>
                        </div>
                        <div class="d-flex flex-column align-items-end justify-content-center">
                            <div class="text-muted d-flex justify-content-end">€ <?php echo htmlspecialchars($cart->getPrice()); ?></div>
                        </div>
                    </li>
                <?php } ?>

                <hr class="mb-4">

                <li class="d-flex justify-content-between align-items-center">
                    <div><strong>Subtotal</strong></div>
                    <div class='d-flex'>€
                        <div class="ms-1" id='subtotal'>
                            <strong>
                                <?php echo sprintf('%.2f', $subtotal); ?>
                            </strong>
                        </div>
                    </div>
                    <input type="hidden" id="subtotal-value-basic" value="<?php echo sprintf('%.2f', $subtotal); ?>">
                </li>

                <li class="d-flex justify-content-between align-items-center">
                    <div>Shipping</div>
                    <span>€
                        <span id="shipping-cost">0.00</span>
                    </span>
                </li>

                <li class="d-flex justify-content-between align-items-center">
                    <div>Promo code</div>
                    <span>€ -
                        <span id="promo-code">0.00</span>
                    </span>
                </li>
                <li class="d-flex justify-content-between align-items-center">
                    <div>Points rewards</div>
                    <div class="d-flex">
                        €
                        <div class="ms-1" id="points-rewards">0.00</div>
                    </div>
                </li>

                <li class="d-flex justify-content-between align-items-center">
                    <div>Total Discount include Quantity Discount</div>
                    <div class='d-flex'>€
                        <div class='ms-1' id="discount">
                            <?php
                            $discount = $totalAmountBasic - $subtotal;
                            echo htmlspecialchars(sprintf('%.2f', $discount));
                            ?>
                        </div>
                    </div>
                    <input type="hidden" id="discount-value-basic" value="<?php
                                                                            $discount = $totalAmountBasic - $subtotal;
                                                                            echo htmlspecialchars(sprintf('%.2f', $discount) . " €");
                                                                            ?>">
                </li>

                <hr class="mb-4">

                <li class="d-flex justify-content-between align-items-center">
                    <strong>Total amount</strong>
                    <strong>
                        <?php echo "<div class='d-flex'>€ <div class='ms-1' id='total-amount'>";
                        echo htmlspecialchars(sprintf('%.2f', $totalAmount));
                        echo "</div></div>";
                        ?>
                    </strong>
                    <input type="hidden" id="total-amount-value-basic" value="<?php echo sprintf('%.2f', $totalAmountBasic); ?>">
                </li>

            </ul>
            <hr class="mb-0">

            <div class="p-2 mb-2">
                <h5 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Coupon</span>
                </h5>
                <div class="input-group">
                    <input type="text" class="form-control" id="promo-code-input" placeholder="Promo code">
                    <div class="input-group-append">
                        <button id="btn-coupon" type="button" class="btn btn-secondary" onclick="useCoupon()">Redeem</button>
                    </div>
                </div>
            </div>
            <hr class="mb-0">
            <div class="p-2 mb-2">
                <h5 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted" id="reward-points">Your Reward Points: <?php echo $rewardPoints ?></span>
                </h5>
                <div class="input-group-append">
                    <button id="btn-rewards" type="button" class="btn btn-secondary w-100" onclick="useRewardPoints()">Use points</button>
                </div>
            </div>

        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Billing address</h4>

            <form id="order-chechkout" class="needs-validation" onsubmit="disableOrderButton()" novalidate action="php_functions/continue_to_checkout.php" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="" value="<?php echo $first_name ?>" readonly required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="" value="<?php echo $last_name; ?>" required readonly>
                        <div class="invalid-feedback">
                            Valid last name is required.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="username" autocomplete="username" name="username" placeholder="Username" required readonly value="<?php echo $username; ?>">
                        <div class="invalid-feedback" style="width: 100%;">
                            Your username is required.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" autocomplete="adddress" name="address" placeholder="1234 Main St" required value="<?php echo $street; ?>" readonly>
                    <div class="invalid-feedback">
                        Please enter your shipping address.
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="country">Country</label>
                        <select class="form-select" name="country" id="country" autocomplete="country" required readonly style="border-radius: 0px; border-color: black;">
                            <option value="<?php echo $country; ?>" selected><?php echo $country; ?></option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid country.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="state">State</label>
                        <select class="form-select" name="state" id="state" readonly required style="border-radius: 0px; border-color: black;">
                            <option value="<?php echo $location; ?>" selected><?php echo $location; ?></option>
                        </select>
                        <div class="invalid-feedback">
                            Please provide a valid state.
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control" name="zip" id="zip" placeholder="" readonly value="<?php echo $postal_code; ?>" required>
                        <div class="invalid-feedback">
                            Zip code required.
                        </div>
                    </div>
                </div>
                <hr class="mb-4">
                <h4 class="mb-3">Shipping</h4>
                <div class="d-block my-3">
                    <div class="col-md-12 mb-3">
                        <label for="delivery">Delivery</label>
                        <select class="form-select" id="delivery" name="delivery" onchange="selectDelivery(<?php echo $totalAmountBasic; ?>,<?php echo $subtotalBasic; ?>)" required style="border-radius: 0px; border-color: black;">
                            <option value="">Choose...</option>
                            <option value="7.50">LPD (7.50 €)</option>
                            <option value="4.50">DHL (4.50 €)</option>
                            <option value="10.50">DHL Express (10.50 €)</option>
                        </select>
                        <input type="hidden" id="delivery-value-company" name="delivery-value-company">
                        <div class="invalid-feedback">
                            Please choose your preferred delivery method.
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="same-address" name="same-address" checked="true" required>
                        <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
                        <div class="invalid-feedback">
                            Please confirm that your shipping address is the same as your billing address.
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="payment-via-invoice" name="payment-via-invoice" checked="true" required>
                        <label class="custom-control-label" for="payment-via-invoice">Payment via invoice</label>
                        <div class="invalid-feedback">
                            Please confirm that you have agreed the payment via invoice.
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="policy-info" id="policy-info" required>
                        <label class="custom-control-label" for="policy-info">Privacy policy read and agreed</label>
                        <div class="invalid-feedback">
                            Please confirm that you have read and agreed to the privacy policy.
                        </div>
                    </div>
                </div>
                <input type="hidden" id="use-points-value" name="use-points-value">
                <input type="hidden" id="use-promo-code-value" name="use-promo-code-value">
                <div class="d-none" id="valide-coupons"></div>
                <hr class="mb-4">
                <button id="checkout-order-btn" class="btn btn-secondary btn-lg btn-block mb-5" type="submit">Continue to checkout</button>
            </form>
        </div>
    </div>
</div>


<script>
    (function() {
        'use strict';

        window.addEventListener('load', function() {
            let forms = document.getElementsByClassName('needs-validation');

            let validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<?php include("main_layout/footer.php"); ?>