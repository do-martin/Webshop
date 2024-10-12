

function validateAndSubmitFormRegistry() {
    let is_valid_data_to_send = true;

    let username = document.getElementById("username").value;
    let username_err = document.getElementById("username_err");
    let first_name = document.getElementById("first_name").value;
    let first_name_err = document.getElementById("first_name_err");
    let last_name = document.getElementById("last_name").value;
    let last_name_err = document.getElementById("last_name_err");
    let address = document.getElementById("address").value;
    let address_err = document.getElementById("address_err");
    let postal_code = document.getElementById("postal_code").value;
    let postal_code_err = document.getElementById("postal_code_err");
    let location = document.getElementById("location").value;
    let location_err = document.getElementById("location_err");
    let country = document.getElementById("country").value;
    let country_err = document.getElementById("country_err");

    if (username.trim() === "") {
        username_err.innerHTML = "Please enter a username.";
        is_valid_data_to_send = false;
    } else {
        console.log(username_err.innerHTML);
        if (username_err.innerHTML != "The username is already taken. Please choose another one.") {
            username_err.innerHTML = "";
        } else {
            is_valid_data_to_send = false;
        }
    }
    if (username.length < 5 && username.indexOf('@') === -1) {
        username_err.innerHTML = "Username must be at least 5 characters long and contain the '@' character.";
        is_valid_data_to_send = false;
    }
    else if (username.length < 5) {
        username_err.innerHTML = "Username must be at least 5 characters long.";
        is_valid_data_to_send = false;
    }
    else if (username.indexOf('@') === -1) {
        username_err.innerHTML = "Username must contain the '@' character.";
        is_valid_data_to_send = false;
    }
    if (first_name.trim() === "") {
        first_name_err.innerHTML = "Please enter a first name.";
        is_valid_data_to_send = false;
    } else {
        first_name_err.innerHTML = "";
    }
    if (last_name.trim() === "") {
        last_name_err.innerHTML = "Please enter a last name.";
        is_valid_data_to_send = false;
    } else {
        last_name_err.innerHTML = "";
    }
    if (address.trim() === "") {
        address_err.innerHTML = "Please enter a address.";
        is_valid_data_to_send = false;
    } else {
        address_err.innerHTML = "";
    }
    if (postal_code.trim() === "") {
        postal_code_err.innerHTML = "Please enter a postal code.";
        is_valid_data_to_send = false;
    } else {
        postal_code_err.innerHTML = "";
    }

    if (!/^\d+$/.test(postal_code)) {
        postal_code_err.innerHTML = "Postal code should contain only numbers.";
        is_valid_data_to_send = false;
    } else {
        postal_code_err.innerHTML = "";
    }
    if (location.trim() === "") {
        location_err.innerHTML = "Please enter a location.";
        is_valid_data_to_send = false;
    } else {
        location_err.innerHTML = "";
    }
    if (country.trim() === "") {
        country_err.innerHTML = "Please enter a country.";
        is_valid_data_to_send = false;
    } else {
        country_err.innerHTML = "";
    }
    if (is_valid_data_to_send) {
        document.getElementById('btn-registry').disabled = true;
        document.getElementById('btn-registry').value = "Processing...";
        document.getElementById("form_send_valid_data_to_server").submit();
    } else {
        return false;
    }
}

function validateAndSubmitFormAddress() {
    let is_valid_data_to_send = true;

    let gender = document.getElementById("gender").value;
    let gender_err = document.getElementById("gender_err");
    let first_name = document.getElementById("first_name").value;
    let first_name_err = document.getElementById("first_name_err");
    let last_name = document.getElementById("last_name").value;
    let last_name_err = document.getElementById("last_name_err");
    let address = document.getElementById("address").value;
    let address_err = document.getElementById("address_err");
    let postal_code = document.getElementById("postal_code").value;
    let postal_code_err = document.getElementById("postal_code_err");
    let location = document.getElementById("location").value;
    let location_err = document.getElementById("location_err");
    let country = document.getElementById("country").value;
    let country_err = document.getElementById("country_err");

    if (gender.trim() === "") {
        gender_err.innerHTML = "Please enter a gender.";
        is_valid_data_to_send = false;
    } else {
        gender_err.innerHTML = "";
    }
    if (first_name.trim() === "") {
        first_name_err.innerHTML = "Please enter a first name.";
        is_valid_data_to_send = false;
    } else {
        first_name_err.innerHTML = "";
    }
    if (last_name.trim() === "") {
        last_name_err.innerHTML = "Please enter a last name.";
        is_valid_data_to_send = false;
    } else {
        last_name_err.innerHTML = "";
    }
    if (address.trim() === "") {
        address_err.innerHTML = "Please enter a address.";
        is_valid_data_to_send = false;
    } else {
        address_err.innerHTML = "";
    }
    if (postal_code.trim() === "") {
        postal_code_err.innerHTML = "Please enter a postal code.";
        is_valid_data_to_send = false;
    } else {
        postal_code_err.innerHTML = "";
    }

    if (!/^\d+$/.test(postal_code)) {
        postal_code_err.innerHTML = "Postal code should contain only numbers.";
        is_valid_data_to_send = false;
    } else {
        postal_code_err.innerHTML = "";
    }
    if (location.trim() === "") {
        location_err.innerHTML = "Please enter a location.";
        is_valid_data_to_send = false;
    } else {
        location_err.innerHTML = "";
    }
    if (country.trim() === "") {
        country_err.innerHTML = "Please enter a country.";
        is_valid_data_to_send = false;
    } else {
        country_err.innerHTML = "";
    }
    if (is_valid_data_to_send) {
        document.getElementById('btn-save-address').disabled = true;
        document.getElementById('btn-save-address').value = "Processing...";
        document.getElementById("form_send_valid_data_to_server").submit();
    } else {
        return false;
    }
}

function validateChangePassword() {
    let password = document.getElementById("password").value;
    let password_err = document.getElementById("password_err");
    let confirm_password = document.getElementById("confirm_password").value;
    let confirm_password_err = document.getElementById("confirm_password_err");
    let screen_width = document.getElementById("screen_width_input").value;
    let screen_height = document.getElementById("screen_height_input").value;

    let returnValue = true;
    if (password === "") {
        password_err.innerHTML = "Please enter a password.";
        returnValue = false;
    }
    if (confirm_password === "") {
        confirm_password_err.innerHTML = "Please confirm your password.";
        returnValue = false;
    }

    if (password !== confirm_password) {
        confirm_password_err.innerHTML = "Passwords do not match.";
        returnValue = false;
    } else {
        let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{9,}$/;
        if (password.length < 9 && !regex.test(password)) {
            password_err.innerHTML = "Your password must be at least 9 characters long and contain at least one uppercase letter, one lowercase letter, and one number.";
            returnValue = false;
        } else if (password.length < 9) {
            password_err.innerHTML = "Your password must be at least 9 characters long.";
            returnValue = false;
        } else if (!regex.test(password)) {
            password_err.innerHTML = "Your password must contain at least one uppercase letter, one lowercase letter, and one number.";
            returnValue = false;
        }
    }

    if (returnValue) {
        let screen = screen_width + " x " + screen_height;
        document.getElementById('btn-change-password').disabled = true;
        document.getElementById('btn-change-password').innerHTML = "Processing...";
        sha512(password).then(hash => {

            const requestData = {
                password: hash,
                screeen_width_height: screen,
                os: getOperatingSystem()
            };

            fetch('../php_functions/changePasswordFunctions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            })
                .then(response => response.json()) // Expecting a JSON response from the server
                .then(data => {
                    if ((data.success == false)) {
                        document.getElementById('btn-change-password').disabled = false;
                        document.getElementById('btn-change-password').innerHTML = "Change password";
                        password_err.innerHTML = "Please enter a new password.";
                    } else {
                        window.location.href = '../welcome.php';
                    }
                })
        });
    }
}

function validateResettingPasswordForm() {
    let username = document.getElementById('username').value.trim();
    let username_err = document.getElementById('username_err');
    if (username === "") {
        username_err.innerHTML = "Please enter your username";
    } else if (username.length < 5 || username.indexOf("@") === -1) {
        username_err.innerHTML = "Your username must have a minimum of 5 characters and include at least one '@' symbol.";
    } else {
        document.getElementById('btn-reset-password').disabled = true;
        document.getElementById('btn-reset-password').innerHTML = "Processing...";
        sha512(generatePassword()).then(hash => {

            const requestData = {
                username: username,
                password: hash
            };

            fetch('../php_functions/resetPasswordFunctions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    window.location.href = '../welcome.php';
                })
                .then(data => {
                    console.log('Response:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    username_err.innerHTML = "An error occurred while resetting the password.";
                });
        });

    }
}

function validateLoginForm() {
    let is_valid_data_to_send = true;

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let username_err = document.getElementById("username_err");
    let password_err = document.getElementById("password_err");
    let screrenWidth = document.getElementById("screen_width_input").value;
    let screenHeight = document.getElementById("screen_height_input").value;

    username_err.innerHTML = "";
    password_err.innerHTML = "";

    if (username.length < 5 && username.indexOf('@') === -1) {
        username_err.innerHTML = "Username must be at least 5 characters long and contain the '@' character.";
        is_valid_data_to_send = false;
    } else if (username.length < 5) {
        username_err.innerHTML = "Username must be at least 5 characters long.";
        is_valid_data_to_send = false;
    } else if (username.indexOf('@') === -1) {
        username_err.innerHTML = "Username must contain the '@' character.";
        is_valid_data_to_send = false;
    }

    let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{9,}$/;
    if (password.length < 9 && !regex.test(password)) {
        password_err.innerHTML = "Your password must be at least 9 characters long and contain at least one uppercase letter, one lowercase letter, and one number.";
        is_valid_data_to_send = false;
    } else if (password.length < 9) {
        password_err.innerHTML = "Your password must be at least 9 characters long.";
        is_valid_data_to_send = false;
    } else if (!regex.test(password)) {
        password_err.innerHTML = "Your password must contain at least one uppercase letter, one lowercase letter, and one number.";
        is_valid_data_to_send = false;
    }

    if (is_valid_data_to_send) {
        document.getElementById('btn-login').disabled = true;
        document.getElementById('btn-login').innerHTML = "Processing...";
        sha512(password).then(hashedPassword => {

            const requestData = {
                username: username,
                password: hashedPassword,
                screen_width: screrenWidth,
                screen_height: screenHeight,
                os: getOperatingSystem()
            };

            $.ajax({
                url: '../php_functions/loginFunctions.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                success: function (response, status, xhr) {
                    if (response) {
                        if (response.TwoFA != "activated") {
                            window.location.href = response.redirectUrl;
                        } else {
                            openModal(response.secret);
                        }
                    } else {
                        console.error('Redirect URL not found in response');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
        // document.getElementById('btn-login').disabled = false;
        // document.getElementById('btn-login').innerHTML = "Sign in";
    }
}

function checkOutOrder() {
    console.log("Order checked out");
    return true;
}

function validateCheckoutForm() {
    window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('needs-validation');

        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
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

function validateCodeAndSubmit() {
    let codeObject = document.getElementById('code');
    let codeValue = codeObject.value;
    let secret = document.getElementById('secret').value;
    let screeen_width_height = document.getElementById('screen_width_input').value + " x " + document.getElementById('screen_height_input').value;
    let userOs = getOperatingSystem();
    document.getElementById('os').value = userOs;

    $.ajax({
        url: 'php_functions/twoFactorAuthFunctions.php',
        type: 'POST',
        data: {
            action: 'verify',
            code: codeValue,
            secret: secret,
            screen_width_height: screeen_width_height,
            os: userOs
        },
        dataType: 'json',
        success: function (response) {
            if (response.success == true) {
                window.location.href = response.redirectUrl;
            } else {
                let invalidCode = document.getElementById('invalid-code');
                invalidCode.innerHTML = "Invalid code. Please try again.";
            }

        },
        error: function (xhr, status, error) {
            console.error('AJAX Request Error:', error);
            $('#username_err').html('<p>Error loading user list. Please try again later.</p>');
        }
    });
}

function openModal(secret) {
    $('#twofaModal').modal('show');
    let secretElement = document.getElementById('secret');
    secretElement.value = secret;
}

function closeModal() {
    $('#twofaModal').modal('hide');
}