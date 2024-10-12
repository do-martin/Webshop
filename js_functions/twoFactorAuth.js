function openModal(contentId) {
    $('#exampleModal').modal('show');
}

function closeModal(contentId) {
    $('#exampleModal').modal('hide');

}

function activate2FA() {
    let state = document.getElementById("btn-activation-2fa");

    if (state.textContent == "2FA Deactivated") {
        state.textContent = "2FA Activated";
        state.className = "btn btn-success mr-2";
        $.ajax({
            url: 'php_functions/twoFactorAuthFunctions.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'activate' },
            success: function (response) {
                let imgWindow = $('#qrCode-window');
                imgWindow.empty().append('<img id="qrCode" src="' + response + '">');
                document.getElementById("setupForm").style.display = "block";
                $('#twofaModal').modal('show');

            },
            error: function (response) {
                alert("Error activating 2FA");
            }
        });
    } else {
        state.textContent = "2FA Deactivated";
        state.className = "btn btn-danger mr-2";
        $.ajax({
            url: 'php_functions/twoFactorAuthFunctions.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'deactivate' },
            success: function (response) {
                if (!response) {
                    alert("Error deactivating 2FA");
                } else {
                    document.getElementById("setupForm").style.display = "hidden";
                }
            },
            error: function (response) {
                alert("Error deactivating 2FA");
            }
        });
    }
}

function setupNew2FA() {
    $.ajax({
        url: 'php_functions/twoFactorAuthFunctions.php',
        type: 'POST',
        dataType: 'json',
        data: { action: 'setup' },
        success: function (response) {
            let imgWindow = $('#qrCode-window');
            imgWindow.empty().append('<img id="qrCode" src="' + response + '">');
        },
        error: function (response) {
            alert("Error setting up 2FA");
        }
    });
}

function verify2FA() {
    let input = document.getElementById("2fa-input").value;

    $.ajax({
        url: 'php_functions/twoFactorAuthFunctions.php',
        type: 'POST',
        dataType: 'json',
        data: { action: 'verify', code: input },
        success: function (response) {
            console.log(response);

        }
    });
}