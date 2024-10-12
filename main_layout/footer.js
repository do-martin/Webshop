function loadOnlineUserList() {
    $.ajax({
        url: 'php_functions/getUserList.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.length == 0) {
                $('#userList').html('<p>No users online.</p>');
            } else {
                let userListHTML = '';
                $.each(response, function (index, user) {

                    userListHTML += '<li>' + user.firstName + " " + user.lastName;
                    userListHTML += '<span class="user-status ml-2 user-online"></span></li>';
                });

                $('#userList').html(userListHTML);
            }

            document.getElementById("users-icon-value").innerHTML = response.length;
            console.log("User list loaded successfully.");
        },
        error: function (xhr, status, error) {
            console.error('AJAX Request Error:', error);
            $('#userList').html('<p>Error loading user list. Please try again later.</p>');
        }
    });
}


// // Get the button and the dropdown menu elements
// const button = document.getElementById('dropdownMenuButton');
// const dropdownMenu = document.querySelector('#dropdownMenuButton + .dropdown-menu');

// if(button != null && dropdownMenu != null) {


// // Add an event listener to the button
// button.addEventListener('click', () => {
//     // Toggle the 'show' class on the dropdown menu
//     dropdownMenu.classList.toggle('show');
// });

// // Add an event listener to the document to hide the dropdown menu when clicking outside
// document.addEventListener('click', (event) => {
//     if (!event.target.matches('#dropdownMenuButton')) {
//         dropdownMenu.classList.remove('show');
//     }
// });


// }
