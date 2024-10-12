function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function shuffleString(str) {
    let array = str.split('');
    let currentIndex = array.length;
    let temporaryValue, randomIndex;

    while (0 !== currentIndex) {

        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array.join('');
}

function getUserScreenResolution() {
    let screen_width = window.screen.width;
    let screen_height = window.screen.height;

    document.getElementById("screen_width_input").value = screen_width;
    document.getElementById("screen_height_input").value = screen_height;
}

function sha512(str) {
    return crypto.subtle.digest("SHA-512", new TextEncoder().encode(str))
        .then(buf => {
            return Array.prototype.map.call(new Uint8Array(buf), x => ('00' + x.toString(16)).slice(-2)).join('');
        });
}

function generatePassword() {
    let chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let length = 9;
    let password = '';

    password += chars.charAt(getRandomInt(26, 51)); // Uppercase letter
    password += chars.charAt(getRandomInt(0, 25));  // Lowercase letter
    password += chars.charAt(getRandomInt(52, 61)); // Number

    for (let i = 0; i < length - 3; i++) {
        password += chars.charAt(getRandomInt(0, chars.length - 1));
    }

    password = shuffleString(password);

    return password;
}

function getUserScreenResolution() {
    let screenWidth = window.screen.width;
    let screenHeight = window.screen.height;

    document.getElementById("screen_width_input").value = screenWidth;
    document.getElementById("screen_height_input").value = screenHeight;
}