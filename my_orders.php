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

$title = "My Orders";

$path = $_SERVER['DOCUMENT_ROOT'];
$year = date("Y");

$orders = "";

$extraScript = '<script>
let currentYear = new Date().getFullYear();
let dropdownList = document.getElementById("dropdown-order-years");

for (let year = currentYear; year >= currentYear - 4; year--) {
    let listItem = document.createElement("li");
    let link = document.createElement("a");
    link.setAttribute("type", "button");
    link.setAttribute("class", "dropdown-item btn-sort-products");
    link.setAttribute("href", "my_orders.php?year=" + year);
    link.textContent = year;
    listItem.appendChild(link);
    dropdownList.appendChild(listItem);
}
</script>';

require_once $path . "/config/config.php";
require_once $path . "/php_functions/my_order_functions.php";
require_once $path . "/models/invoiceModel.php";
require_once $path . "/models/productModel.php";
require_once $path . "/php_functions/checkForFirstLogin.php";

if (isset($_GET['year'])) {
    $year = $_GET['year'];
    $orders = getAllOrders($_SESSION['username'], $year);
} else {
    $orders = getAllOrders($_SESSION['username']);
}
$subtotal = 0;

?>

<?php include("main_layout/header.php"); ?>

<h1 class="w-100 d-flex justify-content-center align-items-center my-order-header">
    Your Order in
    <div class="btn-group h-100 p-0" style="margin-left: 5px;">
        <button type="button" class="btn btn-sort-by dropdown-toggle my-order-header" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight:bolder;">
            <?php echo $year; ?>
        </button>
        <ul id="dropdown-order-years" class="dropdown-menu scrollable-menu dropdown-menu-bg border" role="menu" style="border-radius: 0px;">
        </ul>
    </div>
</h1>

<?php
if (is_iterable($orders) || is_object($orders)) {
    foreach ($orders as $order) { ?>
        <div class="container mt-5 w-50 my-order-width">
            <div class="card mb-3" style="border-radius:0px;">
                <div class="card-header d-flex justify-content-between">
                    <div class="col-md-4">
                        <p class="card-title">Order date: <?php echo $order->getOrderDate(); ?></p>
                    </div>
                    <div class="col-md-4 d-flex justify-content-end">
                        <p class="card-title">Order number: <?php echo $order->getIdInvoiceNumber(); ?></p>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-text d-flex justify-content-between align-items-center mb-3">
                        <div>Your order </div>
                        <?php
                        $orderedArticle = $order->getOrderedArticles();
                        foreach ($orderedArticle as $article) {
                            if ($article->getItemInventory() < $article->getAmount()) {
                                echo '<button id="btn-reorder-' . $order->getIdInvoiceNumber()  . '" type="button" disabled="true" class="btn btn-secondary btn-reorder">Out of stock</button>';
                                break;
                            } else if ($article == $orderedArticle[count($order->getOrderedArticles()) - 1]) {
                                echo '<button id="btn-reorder-' . $order->getIdInvoiceNumber()  . '" type="button" class="btn btn-secondary btn-reorder" onclick="orderAgain(' . $order->getIdInvoiceNumber() . ')">Order again for ' . sprintf("%.2f", $order->getCleanTotal()) . ' €</button>';
                            }
                        }
                        ?>
                    </h5>
                    <?php
                    if (is_iterable($order->getOrderedArticles()) || is_object($order->getOrderedArticles())) {
                        foreach ($order->getOrderedArticles() as $article) { ?>
                            <li class="d-flex justify-content-between lh-condensed" style="border-radius: 0px;">
                                <div>
                                    <h6 class="my-0"><?php echo htmlspecialchars($article->getProductName()); ?></h6>
                                    <div class="text-muted">Article number: <?php echo $article->getItemNumber() ?></div>
                                    <div class="text-muted">Amount <?php echo htmlspecialchars($article->getAmount()); ?></div>
                                </div>
                                <div class="d-flex flex-column align-items-end justify-content-end" style="min-width: 70px;">
                                    <div class="text-muted d-flex align-items-end justify-content-end">€ <?php echo htmlspecialchars(sprintf("%.2f",(sprintf("%.2f", $article->getTotalAmount())) / $article->getAmount())); ?></div>
                                </div>
                            </li>
                    <?php }
                    }
                    ?>

                    <hr class="mb-4">

                    <li class="d-flex justify-content-between align-items-center">
                        <span>Subtotal</span>
                        <span class="text-muted">€ <?php echo htmlspecialchars(sprintf("%.2f", sprintf("%.2f", $order->getSubtotal()) - sprintf("%.2f", $order->getShippingPrice()))); ?></span>
                    </li>

                    <li class="d-flex justify-content-between align-items-center">
                        <span>Shipping price</span>
                        <span class="text-muted">€ <?php
                                                    echo htmlspecialchars(sprintf("%.2f", $order->getShippingPrice())); ?></span>
                    </li>

                    <li class="d-flex justify-content-between align-items-center">
                        <span>Quantity discount</span>
                        <span class="text-muted">€ <?php echo htmlspecialchars(sprintf("%.2f", ($order->getCleanTotal() - $order->getSubtotal()))); ?></span>
                    </li>

                    <li class="d-flex justify-content-between align-items-center">
                        <span>Reward points</span>
                        <span class="text-muted">€ <?php echo htmlspecialchars(sprintf("%.2f", $order->getUsedPoints() / 100 * -0.1)); ?></span>
                    </li>

                    <li class="d-flex justify-content-between align-items-center">
                        <span>Promo Code Sale</span>
                        <span class="text-muted">€ <?php echo htmlspecialchars(sprintf("%.2f", $order->getTotalAmount() - $order->getCleanTotal() + ($order->getUsedPoints() / 100 * 0.1))); ?></span>
                    </li>

                    <hr class="mb-4">

                    <li class="d-flex justify-content-between align-items-center">
                        <strong>Total amount</strong>
                        <strong class="d-flex">
                            <?php echo "<div class='d-flex' style='margin-right:3px;'><div id='total-amount'> ";
                            echo "</div>€</div>";
                            echo htmlspecialchars(sprintf("%.2f", $order->getTotalAmount()));
                            ?>
                        </strong>
                    </li>


                    <?php
                    $orderedArticle = $order->getOrderedArticles();
                    foreach ($orderedArticle as $article) {
                        if ($article->getItemInventory() < $article->getAmount()) {
                            echo '<button type="button" disabled="true" class="btn btn-secondary w-100 btn-reorder-mq mt-3">Out of stock</button>';
                            break;
                        } else if ($article == $orderedArticle[count($order->getOrderedArticles()) - 1]) {
                            echo '<button type="button" class="btn btn-secondary btn-reorder-mq w-100 mt-3" onclick="orderAgain(' . $order->getIdInvoiceNumber() . ')">Order again for ' . sprintf("%.2f", $order->getCleanTotal()) . ' €</button>';
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
<?php
    }
}
?>

<?php include("main_layout/footer.php"); ?>