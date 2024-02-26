// validate number
const phoneNumber = document.getElementById('input-phone');
    phoneNumber.addEventListener('input', function(e) {
       const formattedInputValue = formatPhoneNumber(e.target.value) ;
       e.target.value = formattedInputValue;
        console.log(phoneNumber.value)
    });

function formatPhoneNumber(value) {
    if (!value) return value;
    const number = value.replace(/[^\d]/g,'');
    const numberSize = number.length;

    if (numberSize < 3) return number;
    if (numberSize < 6) {
        return `${number.slice(0, 3)}-${number.slice(3)}`;
    }
    return `${number.slice(0, 3)}-${number.slice(3, 6)}-${number.slice(6,9)}`;
}

// validate email
const emailInput = document.getElementById('input-mail');
const emailError = document.getElementsByClassName('invalid-feedback');

emailInput.addEventListener('input', function () {
    const email = emailInput.value.trim();
    if (!isValidEmail(email)) {
        //console.log('aqui');
        emailError.textContent = 'Email inválido';
    } else {
        //console.log('ali');
        emailError.textContent = '';
    }
});

function isValidEmail(email) {
    // Expresión regular para validar el email
    const emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
    return emailRegex.test(email);
}