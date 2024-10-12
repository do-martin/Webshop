
function getCartFromCookie() {
    let cart = [];
    let cookie = document.cookie;
    if (cookie.includes('cart=')) {
        let jsonStr = cookie.split('cart=')[1].split(';')[0];
        cart = JSON.parse(jsonStr);
    }
    setCartIconValue();
    return cart;
}

function saveCartToCookie(cart) {
    let jsonStr = JSON.stringify(cart);
    document.cookie = "cart=" + jsonStr + ";path=/";
}

function loadCartByUsername() {
    import('../../models/cartModel.js').then(module => {
        let Cart = module.default;

        $.ajax({
            url: 'php_functions/cartFunctions.php',
            type: 'POST',
            data: {
                action: 'getCartItems',
            },
            dataType: 'json',
            success: function (response) {
                if (response !== false && response.length > 0) {
                    carts = [];
                    response.forEach(cartItem => {
                        carts.push(new Cart(cartItem.item_number, cartItem.product_name, cartItem.price, cartItem.amount, cartItem.item_inventory, cartItem.category, cartItem.path, cartItem.gender));
                    });
                    console.log('Response:', carts);
                    document.cookie = 'cart=' + JSON.stringify(carts) + '; path=/';
                    let cart = getCartFromCookie();
                    let checkoutBtn = document.getElementById("btn-cart-check-out");
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = 'Check Out';

                    saveCartToCookie(cart);
                    updateCartUI(cart);
                } else {
                    console.log('Der Warenkorb ist leer.');
                    let emptyCart = [];
                    let checkoutBtn = document.getElementById("btn-cart-check-out");
                    checkoutBtn.disabled = true;
                    checkoutBtn.innerHTML = 'Cart is empty';

                    saveCartToCookie(emptyCart);
                    updateCartUI(emptyCart);
                }

            },
            error: function (xhr, status, error) {
                console.error('AJAX Request Error:', error);
                $('#username_err').html('<p>Error loading user list. Please try again later.</p>');
            }
        });

    }).catch(error => {
        console.error('Error loading cartModel:', error);
    });
}

function insertFromProductPageIntoCart(jsonData) {
    let cart = getCartFromCookie();
    // let newCart = undefined;
    import('../../models/cartModel.js').then(module => {
        let Cart = module.default;
        let cartCurrentAmount = 0;

        let newItem = JSON.parse(jsonData);
        if (newItem.amount <= 0) {
            document.getElementById('minus-plus-to-cart-' + newItem.item_number).classList.add('is-invalid');
            document.getElementById('error-message-' + newItem.item_number).textContent = 'Amount must be greater than 0';
            return;
        } else {
            document.getElementById('minus-plus-to-cart-' + newItem.item_number).classList.remove('is-invalid');
            document.getElementById('error-message-' + newItem.item_number).textContent = '';
            for (let i = 0; i < cart.length; i++) {
                if (cart[i].item_number == newItem.item_number) {
                    cartCurrentAmount = cart[i].amount;
                }
            }
        }
        let currentAmount = 0;
        let cartItem = new Cart(newItem.item_number, newItem.product_name, newItem.price, newItem.amount, newItem.item_inventory, newItem.category, newItem.path, newItem.gender);

        if (newItem.amount == 0) {
            console.log("Amount is 0");
            return;
        }

        if (parseInt(newItem.amount) + parseInt(cartCurrentAmount) > newItem.item_inventory) {
            let exceededItem = document.getElementById('error-message-' + newItem.item_number);
            exceededItem.innerHTML = 'Item amount exceeds inventory';
            console.log("Item amount exceeds inventory");
            return;
        }

        for (let i = 0; i < cart.length; i++) {
            if (cart[i].item_number == newItem.item_number) {
                currentAmount = cart[i].amount;
                if (currentAmount + newItem.amount <= cart[i].item_inventory) {
                    cartItem = new Cart(newItem.item_number, newItem.product_name, newItem.price, newItem.amount + cart[i].amount, newItem.item_inventory, newItem.category, newItem.path, newItem.gender);
                } else {
                    let exceededItem = document.getElementById('error-message-' + newItem.item_number);
                    exceededItem.innerHTML = 'Item amount exceeds inventory';
                    console.log("Item amount exceeds inventory");
                    return;
                }
                break;
            }
        }

        let newAmount = newItem.amount + currentAmount;
        // newCart = cart;

        document.getElementById('btn-insert-item-' + newItem.item_number + '-into-cart').disabled = true;

        $.ajax({
            url: 'php_functions/cartFunctions.php',
            type: 'POST',
            data: {
                action: 'insertOrUpdateCart',
                id_item_num: newItem.item_number,
                prod_name: newItem.product_name,
                amount: newAmount,
                price: newItem.price,
            },
            dataType: 'json',
            success: function (response) {
                if (response == true) {
                    addToClientCart(JSON.stringify(cartItem));
                    console.log('Item added to cart successfully: ', cartItem);
                } else {
                    console.error('Error adding item to cart:', response.error);
                    $('#cart_error').html('<p>Error adding item to cart. Please try again later.</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Request Error:', error);
                $('#cart_error').html('<p>AJAX Request Error. Please try again later.</p>');
            }
        });
        document.getElementById('btn-insert-item-' + newItem.item_number + '-into-cart').disabled = false;
    }).catch(error => {
        console.error('Error loading cartModel:', error);
    });
}

function addToClientCart(jsonData) {
    let cart = getCartFromCookie();
    let newItem = JSON.parse(jsonData);
    let found = false;

    for (let i = 0; i < cart.length; i++) {
        if (cart[i].item_number === newItem.item_number) {
            // if (!isNaN(parseInt(cart[i].amount)) && (cart[i].amount + newItem.amount <= cart[i].item_inventory)) {
            cart[i] = newItem;
            // }
            found = true;
            break;
        }
    }
    if (!found && newItem.amount <= newItem.item_inventory) {
        cart.push(newItem);
    }
    saveCartToCookie(cart);
    updateCartUI(cart);
    document.getElementById('minus-plus-to-cart-' + newItem.item_number).value = '';
}

function removeFromCartByItemNumber(i_number) {
    let cart = getCartFromCookie();
    let item_num = -1;
    for (let i = 0; i < cart.length; i++) {
        if (cart[i].item_number == i_number) {
            item_num = cart[i].item_number;
            cart = cart.filter(item => item.item_number != i_number);
            break;
        }
    }

    $.ajax({
        url: 'php_functions/cartFunctions.php',
        type: 'POST',
        data: {
            action: 'deleteCartItem',
            id_item_num: item_num,
        },
        dataType: 'json',
        success: function (response) {
            if (response == true) {
                saveCartToCookie(cart);
                updateCartUI(cart);
            } else {
                throw new Error('Error deleting item from cart:', response.error);
            }

        },
        error: function (xhr, status, error) {
            console.error('AJAX Request Error:', error);
            $('#username_err').html('<p>Error loading user list. Please try again later.</p>');
        }
    });
}

function minusOrPlusOneByItemNumber(i_number, value) {
    let cart = getCartFromCookie();
    let intValue = parseInt(value);

    let keyInput = document.getElementById('cart-input-via-keyboard-' + i_number);

    for (let i = 0; i < cart.length; i++) {
        if (cart[i].item_number == i_number) {
            if (cart[i].amount + intValue > cart[i].item_inventory || cart[i].amount + intValue < 0) {
                keyInput.classList.add('is-invalid');
                return;
            }
        }
    }

    keyInput.classList.remove('is-invalid');
    let newItem = "";

    for (let i = 0; i < cart.length; i++) {
        if (cart[i].item_number == i_number) {
            if (cart[i].amount + intValue == 0) {
                removeFromCartByItemNumber(i_number);
                cart = cart.filter(item => item.item_number != i_number);
            }


            if (cart[i].amount + value > 0) {
                cart[i].amount = cart[i].amount + intValue;
                let currentAmount = 0;
                for (let i = 0; i < cart.length; i++) {
                    if (cart[i].item_number === i_number) {
                        currentAmount = cart[i].amount;
                        newItem = cart[i];
                        break;
                    }
                }
                $.ajax({
                    url: 'php_functions/cartFunctions.php',
                    type: 'POST',
                    data: {
                        action: 'insertOrUpdateCart',
                        id_item_num: newItem.item_number,
                        prod_name: newItem.product_name,
                        amount: (currentAmount),
                        price: newItem.price,
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response == true) {
                            console.log('Item added to cart successfully.');
                        } else {
                            console.error('Error adding item to cart:', response.error);
                            $('#cart_error').html('<p>Error adding item to cart. Please try again later.</p>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Request Error:', error);
                        $('#cart_error').html('<p>AJAX Request Error. Please try again later.</p>');
                    }
                });

            }
            else {
                if (cart[i].amount > 1) {
                    let newItem = cart[i];
                    cart[i].amount = cart[i].amount - 1;

                    $.ajax({
                        url: 'php_functions/cartFunctions.php',
                        type: 'POST',
                        data: {
                            action: 'insertOrUpdateCart',
                            id_item_num: newItem.item_number,
                            prod_name: newItem.product_name,
                            amount: cart[i].amount,
                            price: newItem.price,
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response == true) {
                                console.log('Item added to cart successfully.');
                            } else {
                                console.error('Error adding item to cart:', response.error);
                                $('#cart_error').html('<p>Error adding item to cart. Please try again later.</p>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Request Error:', error);
                            $('#cart_error').html('<p>AJAX Request Error. Please try again later.</p>');
                        }
                    });

                } else {
                    removeFromCartByItemNumber(i_number);
                    cart = cart.filter(item => item.item_number != i_number);
                }
                break;
            }
        }
    }

    saveCartToCookie(cart);
    updateCartUI(cart);
}

function updateCartUI(cart) {
    let subtotal = 0;
    let cartDiv = $('#cart');
    cartDiv.empty();
    if (cart == null || cart == undefined) {
    } else {
        import('../../models/cartModel.js').then(module => {
            let Cart = module.default;
            let total = 0;
            let subtotal = 0;
            let discount = 0;

            cart.forEach(function (item) {
                let insertCart = undefined;
                insertCart = new Cart(item.item_number, item.product_name, item.price, item.amount, item.item_inventory, item.category, item.path, item.gender)

                if (insertCart != undefined) {
                    item = insertCart;
                }

                let itemDiv = $('<div class="cart-item">');
                itemDiv.append(`    <div class="d-flex justify-content-center align-items-center mb-3">
                                <div class="w-25">
                                    <img class="h-75" src=" `+ item.path + `" alt=" ` + item.product_name + `">
                                </div>
                                <div class="w-50">
                                    <div class="item-name"> `+ item.product_name + `</div>
                                    <div class="item-number"> item number:  `+ item.item_number + `</div>
                                    <div class="item inventory"> item inventory: ` + item.item_inventory + `</div>
                                    <div class="item-price">€` + item.price + `</div>
                                </div>
                            </div>
                        </div>`);



                itemDiv.append(`<div class="input-group mb-3">
                            <button class="btn btn-outline-secondary" type="button" onclick="minusOrPlusOneByItemNumber(` + item.item_number + `, -1)" id="minus-btn-item-number` + item.item_number + `">-</button>
                            <input min="0" max="`+ item.item_inventory + `" onChange="inputCartOnChange(` + item.item_number + `, ` + item.item_inventory + `, ` + item.amount + `)" type="number" step="1" name="input-` + item.amount + `" id="cart-input-via-keyboard-` + item.item_number + `" class="form-control text-center" value="` + item.amount + `" aria-label="Number" aria-describedby="minus-btn plus-btn">
                            <button class="btn btn-outline-secondary" type="button" onclick="minusOrPlusOneByItemNumber(` + item.item_number + `, 1)" id="plus-btn-item-number` + item.item_number + `" class="form-control text-center">+</button>
                            <button onclick="removeFromCartByItemNumber(` + item.item_number + `)" style="margin-left: 10px;" type="button" class="btn btn-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                </svg>
                            </button>
                        </div>`);
                total = (parseFloat(total) + parseFloat(item.totalAmountAfterSale)).toFixed(2);
                subtotal = (parseFloat(subtotal) + parseFloat(item.totalAmount)).toFixed(2);
                cartDiv.append(itemDiv);
            });

            discount = (parseFloat(subtotal) - parseFloat(total)).toFixed(2);
            document.getElementById('products-total-amount').innerHTML = '€ ' + total;
            document.getElementById('products-subtotal').innerHTML = '€ ' + subtotal;
            document.getElementById('products-discount').innerHTML = '- €' + discount;
            //test
            // saveCartToCookie(cart);

            setCartIconValue();
            setCartBtn();

        }).catch(error => {
            console.error('Error loading cartModel:', error);
        });

    }
}

function inputCartOnChange(itemNumber, itemInventory, currentAmount) {
    let keyInput = document.getElementById('cart-input-via-keyboard-' + itemNumber);

    if (keyInput.value == "" || keyInput.value < 0 || keyInput.value % 1 != 0 || keyInput.value > itemInventory) {
        keyInput.classList.add('is-invalid');
        return;
    } else {
        keyInput.classList.remove('is-invalid');
        let valueInput = parseInt(keyInput.value);
        if (valueInput == 0) {
            removeFromCartByItemNumber(itemNumber);
        } else {
            let currentAmountInt = parseInt(currentAmount);
            let newAmount = valueInput - currentAmountInt;

            minusOrPlusOneByItemNumber(itemNumber, newAmount);
        }

    }
}

function setCartIconValue() {
    let cart = [];
    let cookie = document.cookie;
    if (cookie.includes('cart=')) {
        let jsonStr = cookie.split('cart=')[1].split(';')[0];
        cart = JSON.parse(jsonStr);
    }
    let totalProducts = 0;
    if (cart.length > 0) {
        cart.forEach(function (item) {
            totalProducts += item.amount;
        });
    } else {
        totalProducts = 0;
    }
    let cartIconValue = document.getElementById('cart-icon-value');
    if (cartIconValue != null) {
        cartIconValue.innerHTML = totalProducts;
    }
}

function setCartBtn() {
    let cart = getCartFromCookie();
    let btn = document.getElementById('btn-cart-check-out');
    if (cart.length > 0) {
        btn.disabled = false;
        btn.innerHTML = 'Check Out';
    } else {
        btn.disabled = true;
        btn.innerHTML = 'Cart is empty';
    }
}

function deleteCart() {
    document.cookie = 'cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    let cart = getCartFromCookie();
    saveCartToCookie(cart);
    updateCartUI(cart);
}

function plusMinusActionhandler(number, itemNumber, itemInventory) {
    let inputString = 'minus-plus-to-cart-' + itemNumber.toString();
    let inputNumber = document.getElementById(inputString).value;
    if (inputNumber == "") {
        inputerNumber = 0;
    } else {
        inputNumber = parseInt(inputNumber);
    }
    if (number > 0) {
        inputNumber = inputNumber + 1;
    } else if (inputNumber >= 1) {
        inputNumber = inputNumber - 1;
    }
    document.getElementById(inputString).value = inputNumber;
}

function calculateTotalPriceAfterDiscount(price, amount) {
    let total = 0;

    if (amount >= 10) {
        let discount = 0.2;
        total = total + amount * price * (1 - discount);
    } else if (amount >= 5) {
        let discount = 0.1;
        total = total + amount * price * (1 - discount);
    } else {
        total = total + amount * price;
    }
    return total;
}
