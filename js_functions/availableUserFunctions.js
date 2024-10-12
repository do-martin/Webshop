function loadAvailableUsernames() {
    let availableUsername = true;
    let username = document.getElementById("username").value;
    let username_err = document.getElementById("username_err");

    $.ajax({
        url: 'php_functions/registerFunction.php',
        type: 'GET',
        data: { action: 'loadAvailableUsernames' },
        dataType: 'json',
        success: function (response) {
            $.each(response, function (index, user) {
                if (username === user.username) {
                    availableUsername = false;
                }
            });

            if (username.trim() === "") {
                username_err.innerHTML = "Please enter a username.";
            }
            else if (username.length < 5 && username.indexOf('@') === -1) {
                username_err.innerHTML = "Username must be at least 5 characters long and contain the '@' character.";
            } else if (username.length < 5) {
                username_err.innerHTML = "Username must be at least 5 characters long.";
            } else if (username.indexOf('@') === -1) {
                username_err.innerHTML = "Username must contain the '@' character.";
            } else if (!availableUsername) {
                username_err.innerHTML = "The username is already taken. Please choose another one.";
            } else {
                username_err.innerHTML = "";
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Request Error:', error);
            $('#username_err').html('<p>Error loading user list. Please try again later.</p>');
        }
    });
}

function showLiveToast() {
    let liveToast = new bootstrap.Toast(document.getElementById('liveToast'));
    liveToast.show();
}