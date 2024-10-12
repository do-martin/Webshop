function extractNumber(str) {
    try {
        return str.match(/[-+]?\d*\.?\d+/g).map(Number)[0];
    } catch (e) {
        let arr = [];
        arr.push(0);
        return arr[0];
    }
}

function orderAgain(orderID) {
    let btnReorder = document.getElementsByClassName('btn-reorder');
    Array.prototype.forEach.call(btnReorder, function (btn) {
        btn.disabled = true;
        btn.textContent = 'Processing...';
    });

    document.getElementById('btn-reorder-' + orderID).disabled = true;
    document.getElementById('btn-reorder-' + orderID).textContent = 'Ordering...';

    $.ajax({
        url: 'php_functions/orderAgainFunctions.php',
        type: 'POST',
        data: {
            action: 'order_again',
            orderID: orderID
        },
        dataType: 'json',
        success: function (response) {
            console.log('AJAX Request Success:', response);
            window.location.href = response;
        },
        error: function (xhr, status, error) {
            console.error('AJAX Request Error:', error);
            $('#username_err').html('<p>Error loading user list. Please try again later.</p>');
        }
    });
}

function getDeliveryPrice() {
    let deliveryPrice = document.getElementById('delivery').value;
    let valueDeliveryPrice = "";
    if (deliveryPrice == "") {
        valueDeliveryPrice = 0;
    } else {
        valueDeliveryPrice = deliveryPrice;
    }

    return valueDeliveryPrice;
}

function getCouponSales() {
    let promoCodes = document.getElementsByClassName('valide-coupon-insertion');
    let promoCodeValue = 0;
    for (let i = 0; i < promoCodes.length; i++) {
        promoCodeValue += parseFloat(promoCodes[i].value);
    }
    let uiCoupon = document.getElementById('promo-code');
    uiCoupon.textContent = parseFloat(promoCodeValue).toFixed(2);

    return parseFloat(promoCodeValue).toFixed(2);
}

function getRewardPointsSale() {
    let rewardPoints = document.getElementById('use-points-value');
    let rewardPointsValue = extractNumber(document.getElementById('reward-points').textContent);    // 300 points
    let sale = Math.floor(rewardPointsValue / 100) / 10;

    if (rewardPoints.textContent != 'true') {
        return 0.00;
    } else {
        return parseFloat(sale).toFixed(2);
    }
}

function calculateTotals(/*calculateWithRewardPoints, deliveryPricet*/) {
    let deliveryPrice = getDeliveryPrice();
    let couponSales = getCouponSales();
    let rewardPointsSale = getRewardPointsSale();

    let subtotal = extractNumber(document.getElementById('subtotal-value-basic').value); // 500 €
    let totalUI = document.getElementById('total-amount');      // 300 €
    let discountUI = document.getElementById('discount');    // 200 €
    let subtotalUI = document.getElementById('subtotal');
    let pointsRewardsUI = document.getElementById('points-rewards');
    pointsRewardsUI.textContent = (-parseFloat(rewardPointsSale)).toFixed(2);
    let totalAmountBasic = extractNumber(document.getElementById('total-amount-value-basic').value);

    totalUI.textContent = parseFloat(totalAmountBasic + parseFloat(deliveryPrice) - parseFloat(couponSales) - parseFloat(rewardPointsSale)).toFixed(2);
    discountUI.textContent = (parseFloat(totalUI.textContent) - parseFloat(subtotal) - parseFloat(deliveryPrice)).toFixed(2);
    // subtotalUI.textContent = (parseFloat(subtotal) + parseFloat(deliveryPrice)).toFixed(2);
    subtotalUI.textContent = parseFloat(subtotal).toFixed(2);
}

function selectDelivery(standardTotalAmount, standardSubtotal) {
    let deliveryPrice = document.getElementById('delivery').value;
    let deliveryCompany = document.getElementById('delivery-value-company');

    document.getElementById('shipping-cost').textContent = deliveryPrice;

    if (deliveryPrice == 7.5) {
        deliveryCompany.value = "LPD";
        deliveryCompany.textContent = "LPD";
    } else if (deliveryPrice == 4.5) {
        deliveryCompany.value = "DHL";
        deliveryCompany.textContent = "DHL";
    } else if (deliveryPrice == 10.5) {
        deliveryCompany.value = "DHL Express";
        deliveryCompany.textContent = "DHL Express";
    } else {
        deliveryCompany.value = "";
        deliveryCompany.textContent = "";
        document.getElementById('shipping-cost').textContent = (0.00).toFixed(2);
    }

    calculateTotals();
}

function useRewardPoints() {
    let rewardPoints = document.getElementById('use-points-value');
    let btnReward = document.getElementById('btn-rewards');

    if (rewardPoints.textContent != 'true') {
        btnReward.className = 'btn btn-success w-100';
        btnReward.textContent = 'Reward Points Used';
        rewardPoints.textContent = true;
        rewardPoints.value = true;
    } else {
        btnReward.className = 'btn btn-secondary w-100';
        btnReward.textContent = 'Reward Points Unused';
        rewardPoints.textContent = false;
        rewardPoints.value = false;
    }

    calculateTotals();
}

function useCoupon() {
    let inputCoupon = document.getElementById('promo-code-input');
    let hiddenCoupon = document.getElementById('use-promo-code-value');
    let sale = 0;

    $.ajax({
        url: 'php_functions/couponFunctions.php',
        type: 'POST',
        data: {
            action: 'use_coupon',
            coupon: inputCoupon.value
        },
        dataType: 'json',
        success: function (response) {
            console.log('AJAX Request Success:', response);
            if (response.error != 'No coupons.') {
                let coupons = document.getElementById('valide-coupons');
                //check if coupon already exists
                let couponsList = document.getElementsByClassName('valide-coupon-insertion');
                for (let i = 0; i < couponsList.length; i++) {
                    if (couponsList[i].id == response.promo_code) {
                        return;
                    }
                }

                let newInputElement = document.createElement('input');
                newInputElement.className = 'valide-coupon-insertion';
                newInputElement.type = 'hidden';
                newInputElement.id = response.promo_code;
                newInputElement.value = response.sale;
                coupons.appendChild(newInputElement);

                // konvertiere in ein array und füge den neuen Gutschein hinzu
                let couponsArray = hiddenCoupon.value ? hiddenCoupon.value.split(',') : [];
                for (let i = 0; i < couponsArray.length; i++) {
                    if (couponsArray[i] == response.promo_code) {
                        return;
                    }
                }
                couponsArray.push(response.promo_code);
                if (couponsArray.length > 0) {
                    hiddenCoupon.value = couponsArray.join(',');
                } else {
                    hiddenCoupon.value = response.promo_code;
                }
                calculateTotals();
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Request Error:', error);
            $('#username_err').html('<p>Error loading user list. Please try again later.</p>');
        }
    });

    // hiddenCoupon.value = inputCoupon.value;
}

function disableOrderButton() {
    let form = document.getElementById('order-chechkout');
    if (form.checkValidity()) {
        document.getElementById('checkout-order-btn').disabled = true;
        document.getElementById('checkout-order-btn').textContent = 'Ordering...';
    }
}