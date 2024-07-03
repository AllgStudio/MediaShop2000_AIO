function validateLoginForm() {
    var form = document.getElementById('loginForm');
    var username = form.username.value;
    var password = form.password.value;

    // check usename min length 3
    if (username.length < 3) {
        document.getElementById('error-msg').innerText = 'Il nome utente deve contenere almeno 3 caratteri.';
        document.getElementById('username').focus();
        return false;
    }

    // check password min length 3
    if (password.length < 3) {
        document.getElementById('error-msg').innerText = 'La password deve contenere almeno 3 caratteri.';
        document.getElementById('password').focus();
        return false;
    }

    return true;
}

function validateRegisterForm() {
    var form = document.getElementById('registerForm');
    var username = form.username.value;
    var password = form.password.value;
    var confirmPassword = form.password_repeat.value;
    var email = form.email.value;

    // check usename min length 3
    if (username.length < 3) {
        document.getElementById('error-msg').innerText = 'Il nome utente deve contenere almeno 3 caratteri.';
        document.getElementById('username').focus();
        return false;
    }

    // check password min length 3
    if (password.length < 3) {
        document.getElementById('error-msg').innerText = 'La password deve contenere almeno 3 caratteri.';
        document.getElementById('password').focus();
        return false;
    }

    // check password match
    if (password !== confirmPassword) {
        document.getElementById('error-msg').innerText = 'Le password non corrispondono.';
        //document.getElementById('password_repeat').focus();
        return false;
    }

    // check email
    if (email.length < 5 || email.indexOf('@') === -1 || email.indexOf('.') === -1) {
        document.getElementById('error-msg').innerText = 'Email non valida.';
        document.getElementById('email').focus();
        return false;
    }

    return true;
}

function validateEditUserForm(){
    var form = document.getElementById('editUserForm');
    var email = form.new_email.value;
    // check email
    if (email.length < 5 || email.indexOf('@') === -1 || email.indexOf('.') === -1) {
        document.getElementById('error-msg').innerText = 'Email non valida.';
        document.getElementById('emailInput').focus();
        return false;
    }

    return true;
}
