const signUP = document.getElementById('sign-up')
const signIn = document.getElementById('sign-in')
const loginIn = document.getElementById('login_in')
const loginUp = document.getElementById('login_up')
console.log(sessionStorage.getItem("todo"));
console.log(20);
if (sessionStorage.getItem("todo") == 'signup') {
    loginIn.classList.remove('block');
    loginUp.classList.remove('none');

    loginIn.classList.add('none');
    loginUp.classList.add('block');
}
signUP.addEventListener('click', () => {
    loginIn.classList.remove('block');
    loginUp.classList.remove('none');

    loginIn.classList.add('none');
    loginUp.classList.add('block');
})
signIn.addEventListener('click', () => {
    loginIn.classList.remove('none');
    loginUp.classList.remove('block');

    loginIn.classList.add('block');
    loginUp.classList.add('none');
})

$(document).ready(function () {
    $('#login').on('click', function (event) {
        event.preventDefault();
        process('login', 'login_in');
    });

    $('#registration').on('click', function (event) {
        event.preventDefault();
        process('registration', 'login_up');
    });

    function process(action, formName) {
        console.log(action, formName);
        var form = document.getElementById(formName);
        var formData = new FormData(form);
        formData.append('action', action);
        // Check file selected or not
        $.ajax({
            url: './ajax/authentication.ajax.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#warning_sign').addClass('text-center');
                $('#warning_signup').addClass('text-center');
                console.log(response);
                var obj = JSON.parse(response);
                if (obj['problem'] == 'ok') {
                    sessionStorage.setItem("user", "login");
                    if (obj['role'] == '1') window.location.href = "./index.php";
                    else window.location.href = "./_admin.php";
                } else if (obj['problem'] == 'reg-ok') {
                    $('#warning_signup').removeClass('text-danger');
                    $('#warning_signup').addClass('text-success');
                    $('#warning_signup').html("Registration Successful.Please Login");
                } else {
                    if (action == 'login') {
                        $('#warning_sign').addClass('text-danger');
                        $('#warning_sign').html(obj['problem']);
                    } else {
                        $('#warning_signup').addClass('text-danger');
                        $('#warning_signup').html(obj['problem']);
                    }

                }
            }
        });

    }
});