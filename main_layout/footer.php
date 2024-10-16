</main>
<footer class="footer mt-auto py-3 text-light text-center d-flex justify-content-center align-items-center" style="z-index: 10; bottom: 0; width: 100%; min-height:10%; background-color: rgb(0 0 0 / 80%) !important;">
    <div class="container" id="legal-disclosure">
        <div class="row">
            <div class="col-sm-4">
                <a class="a-street" style="color:white;" href="" target="_blank">

                    <h5 class="mb-1 footer-header">Styleshop</h5>
                    <div class="footer-body">Clothing according to the latest trend.</div>

                    <div class="footer-body">YourAdress 10</div>
                    <div class="footer-body">00000 YourCity</div>
                </a>
            </div>
            <div class="col-sm-4 footer-header">
                <h5 class="mb-1 footer-header">We are open:</h5>
                <div class="footer-body">Monday to Thursday from 9 a.m. to 4 p.m.</div>
                <div class="footer-body">Friday from 9 a.m. to 2 p.m.</div>
            </div>
            <div class="col-sm-4">
                <h5 class="mb-1 footer-header">Contact us:</h5>
                <div>
                    <a class="a-tel footer-body" href="tel:07121000000" style="color:white;">
                        Telefon: 07121 - 00 00 00
                    </a>
                </div>
                <a class="a-email footer-body" href="mailto::yourMail@yourMail.de" style="color:white;">
                    E-Mail: :yourMail@yourMail.de
                </a>
            </div>
        </div>
    </div>
    <input type="hidden" name="screen_width" id="screen_width_input">
    <input type="hidden" name="screen_height" id="screen_height_input">
</footer>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js_functions/twoFactorAuth.js"></script>
<script src="../js_functions/validateAndSubmitFunctions.js"></script>
<script src="../js_functions/availableUserFunctions.js"></script>
<script src="../js_functions/cartsFunctions.js"></script>
<script src="../js_functions/stylingFunctions.js"></script>
<script src="../js_functions/orderFunctions.js"></script>
<script src="../main_layout/footer.js"></script>
<script src="../js_functions/redirectFunctions.js"></script>

<?php
if (isset($extraScript)) {
    echo $extraScript;
}
?>

<script>
    const button = document.getElementById('dropdownMenuButton');
    const dropdownMenu = document.querySelector('#dropdownMenuButton + .dropdown-menu');

    if (button != null && dropdownMenu != null) {

        button.addEventListener('click', () => {
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', (event) => {
            if (!event.target.matches('#dropdownMenuButton')) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
    getUserScreenResolution();

    <?php if (isset($extraJsFunctions)) {
        echo $extraJsFunctions;
    } ?>
</script>

<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
?>
    <script>
        setCartIconValue();
        loadCartByUsername();
        setInterval(function() {
            loadOnlineUserList();
        }, 1000);
    </script>
<?php }
?>

</body>

</html>