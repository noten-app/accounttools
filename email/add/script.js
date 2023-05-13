function login() {
    $.ajax({
        url: 'login.php',
        type: 'POST',
        data: {
            username: $('#username').val(),
            password: $('#password').val()
        },
        success: function(data) {
            if (data == 'success') {
                document.getElementById("eMailForm").style.display = "block";
                document.getElementById("userForm").style.display = "none";
            } else if (data == 'wrongpass') {
                document.getElementById("wrongPass").style.display = "block";
                document.getElementById("userForm").style.display = "none";
            } else if (data == 'emailset') {
                document.getElementById("alreadySet").style.display = "block";
                document.getElementById("userForm").style.display = "none";
            } else {
                console.log(data);
            }
        }
    });
}

function addEmail() {
    $.ajax({
        url: 'addEmail.php',
        type: 'POST',
        data: {
            username: $('#username').val(),
            password: $('#password').val(),
            email: $('#email').val()
        },
        success: function(data) {
            console.log(data);
            if (data == 'success') {
                location.assign("https://app.noten-app.de/");
            } else {
                console.log(data);
            }
        }
    });
}