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

function validateCheckoutForm(){
    var form = document.getElementById('checkoutForm');
    var name = form.name.value;
    var surname = form.surname.value;
    var address = form.address.value;
    var city = form.city.value;
    var cap = form.zip.value;
    var phone = form.phone.value;

    var card = form.card.value;
    var cvv = form.cvv.value;
    var exp = form.expiry.value;


    if (name.length < 2) {
        document.getElementById('error-msg').innerText = 'Nome non valido.';
        document.getElementById('name').focus();
        return false;
    }

    if (surname.length < 2) {
        document.getElementById('error-msg').innerText = 'Cognome non valido.';
        document.getElementById('surname').focus();
        return false;
    }
    if(email.length < 5 || email.indexOf('@') === -1 || email.indexOf('.') === -1){
        document.getElementById('error-msg').innerText = 'Email non valida.';
        document.getElementById('email').focus();
        return false;
    }

    if (address.length < 2) {
        document.getElementById('error-msg').innerText = 'Indirizzo non valido.';
        document.getElementById('address').focus();
        return false;
    }

    if (city.length < 2) {
        document.getElementById('error-msg').innerText = 'Città non valida.';
        document.getElementById('city').focus();
        return false;
    }

    var capPatern = /^[0-9]{5}$/;
    if (!capPatern.test(cap)) {
        document.getElementById('error-msg').innerText = 'CAP non valido.';
        document.getElementById('zip').focus();
        return false;
    }
    var phonePatern = /^[0-9]{10}$/;
    if (!phonePatern.test(phone)) {
        document.getElementById('error-msg').innerText = 'Telefono non valido.';
        document.getElementById('phone').focus();
        return false;
    }

    var cardPatern = /^[0-9]{16}$/;
    if (!cardPatern.test(card)) {
        document.getElementById('error-msg').innerText = 'Numero carta non valido.';
        document.getElementById('card').focus();
        return false;
    }

    var cvvPatern = /^[0-9]{3}$/;

    if (!cvvPatern.test(cvv)) {

        document.getElementById('error-msg').innerText = 'CVV non valido.';
        document.getElementById('cvv').focus();
        return false;
    }

    var expPatern = /^[0-9]{2}[/][0-9]{2}$/;
    if (!expPatern.test(exp)) {
        document.getElementById('error-msg').innerText = 'Data scadenza non valida.';
        document.getElementById('expiry').focus();
    }
}


function validateNewProduct(){
    var form = document.getElementById('newProductForm');
    var product_name = form.product_name.value;
    var category = form.category.value;
    var brand = form.brand.value;
    var color = form.color.value;
    var price = form.price.value;
    var price_discouted = form.price_discouted.value;
    var description = form.description.value;


    // check product name
    if (product_name.length < 3) {
        document.getElementById('error-msg').innerText = 'Il nome del prodotto deve contenere almeno 3 caratteri.';
        document.getElementById('product_name').focus();
        return false;
    }

    // check category
    if (!category) {
        document.getElementById('error-msg').innerText = 'La categoria non può essere vuota.';
        document.getElementById('category').focus();
        return false;
    }

    // check brand
    if (!brand) {
        document.getElementById('error-msg').innerText = 'Il marca non può essere vuoto.';
        document.getElementById('brand').focus();
        return false;
    }


    var colors = color.split(',');
    for (var i = 0; i < colors.length; i++) {
        if(colors[i].startsWith(' ') || colors[i].endsWith(' ') || colors[i].trim() == ''){
            document.getElementById('error-msg').innerText = 'Il colore non è valido.';
            document.getElementById('color').focus();
            return false;
        }
    }

    // check price
    if (price < 0) {
        document.getElementById('error-msg').innerText = 'Il prezzo non può essere negativo.';
        document.getElementById('price').focus();
        return false;
    }

    // check price discounted
    if (price_discouted < 0 || price_discouted > price) {
        document.getElementById('error-msg').innerText = 'Il prezzo scontato non può essere negativo o maggiori del prezzo.';
        document.getElementById('price_discounted').focus();
        return false;
    }
    // check description
    if (description.length < 10) {
        document.getElementById('error-msg').innerText = 'La descrizione deve contenere almeno 10 caratteri.';
        document.getElementById('description').focus();
        return false;
    }

    return true;
}