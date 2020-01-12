'use strict'

var stripe = Stripe('pk_test_f7l9SPnDw1GgKdxtLfH3TIuI00wq2WZenI');
var elements = stripe.elements();

var style = {
    base: {
        fontSize: '19px',
        color: '#32325d'
    }
};

var card = elements.create('card', {style: style});
card.mount('#card-element');

card.addEventListener('change', function (event) {
    var displayError = document.getElementById('card-error');
    if (event.error) {
        displayError.textContent = event.error.message
    } else {
        displayError.textContent = ''
    }
})

var form = document.getElementById('payment-form');
form.addEventListener('submit', function (event) {
    event.preventDefault();

    stripe.createToken(card).then(function (result) {
        if (result.error) {
            var errorElement = document.getElementById('card-error');
            errorElement.textContent = result.error.message;
        } else {
            stripeTokenHandler(result.token);
        }
    })
});

function stripeTokenHandler(token) {
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    form.submit();
}