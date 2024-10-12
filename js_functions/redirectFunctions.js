function redirectLogout() {
    let windowHeight = window.screen.height;
    let windowWidth = window.screen.width;

    window.location.href = "../logout.php?screen_height=" + windowHeight + "&screen_width=" + windowWidth + "&os=" + getOperatingSystem();
}

function getOperatingSystem() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }
    if (/win/i.test(userAgent)) {
        return "Windows";
    }
    if (/android/i.test(userAgent)) {
        return "Android";
    }
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }
    if (/mac/i.test(userAgent)) {
        return "Mac OS";
    }
    if (/linux/i.test(userAgent)) {
        return "Linux";
    }
    return "unbekanntes Betriebssystem";
}


function redirectCheckoutPage() {
    let cart = getCartFromCookie();
    if (cart.length != 0) {
        document.getElementById('btn-cart-check-out').disabled = true;
        document.getElementById('btn-cart-check-out').innerHTML = "Processing...";
        window.location.href = "../checkout.php";
    }
}

function scrollToId(id) {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}