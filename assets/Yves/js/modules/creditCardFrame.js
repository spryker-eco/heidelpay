/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

var $ = require('jquery');

var paymentIframeSelector = '#paymentIframe';
var paymentFrameFormSelector = '#payment-form';
var paymentSelectionSelector = 'input[name="paymentForm[paymentSelection]"]:checked';
var creditCardPaymentOptionSelector = 'input[name="paymentForm[heidelpayCreditCardSecure][selected_payment_option]"]';
var checkedCreditCardPaymentOptionSelector = creditCardPaymentOptionSelector + ':checked';
var paymentOptionDivsSelector = 'div.payment-option';

var paymentIframe = $(paymentIframeSelector).get(0);
var targetOrigin = getDomainFromUrl(paymentIframe.src);
var paymentFrameForm = $(paymentFrameFormSelector).get(0);

/**
 * Add an event listener to from submit, which will execute the sendMessage function
 */

function getDomainFromUrl(url) {
    var arr = url.split("/");
    return arr[0] + "//" + arr[2];
}

function isCreditCardNewRegistrationNotActive() {
    return ($(paymentSelectionSelector).val() !== 'heidelpayCreditCardSecure') ||
        ($(checkedCreditCardPaymentOptionSelector).val() !== 'new-registration')
}

function serializeIframeForm() {
    data = {};
    for (var i = 0, len = paymentFrameForm.length; i < len; ++i) {
        var input = paymentFrameForm[i];
        if (input.name) { data[input.name] = input.value; }
    }
    return data;
}

function showActivePaymentOption() {
    $(paymentOptionDivsSelector).hide();

    var activeOptionValue = $(checkedCreditCardPaymentOptionSelector).val();

    if (activeOptionValue !== undefined) {
        $('#payment-option-' + activeOptionValue).show();
    }
}

function init(){
    $(paymentFrameForm).submit(function(event){
        if (isCreditCardNewRegistrationNotActive()) {
            return true;
        }
        event.preventDefault();
        var data = serializeIframeForm();
        var paymentIframe = $(paymentIframeSelector).get(0);
        paymentIframe.contentWindow.postMessage(JSON.stringify(data), targetOrigin);
    });

    $(creditCardPaymentOptionSelector).click(function(){
        showActivePaymentOption();
    });

    showActivePaymentOption();
}

$(document).ready(init);

