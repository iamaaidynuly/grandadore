import flatpickr from "flatpickr";
import {Russian} from "flatpickr/dist/l10n/ru.js"

window.flatpickr = flatpickr;

window.flatpickrRussianLocalization = Russian;

$('.personal-info-bar-btn').click(function () {
    $('.personal-info-button').toggleClass('active');
    $('.personal-info-button >.toggle-wrap-personal-info').toggleClass('active');
    $('.personal-info-left-bar').toggleClass('d-block');
    $('.step-section').toggleClass('d-none');
})
$(document).on("click", function (event) {
    if ($('.personal-info-button').hasClass('active')) {

        if ($(event.target).closest('.personal-info-bar-btn').length === 0) {
            if ($(event.target).closest('.personal-info-button').length === 0) {

                if ($('.personal-info-left-bar').hasClass('d-block')) {
                    $('.personal-info-left-bar').toggleClass('d-block');
                    $('.personal-info-button').toggleClass('active');
                    $('.personal-info-button > .toggle-wrap-personal-info').toggleClass('active');
                    $('body').toggleClass('overflow-hidden');
                }
            }
        }
    }
});

$(function () {
    $('.left-bar > ul >li').each(function () {
        if ($(this).hasClass('active')) {
            $('.btn-section').append($(this).text());
        }
    })
});
let phoneCodeWasSent = false;
let emailCodeWasSent = false;

$('#phoneChangingTrigger').click(function () {
    let phoneInput = $('#cabinet-phone');
    let codeInput = $('#phoneCode');
    let phoneAlert = $('#phoneAlert');

    phoneAlert.empty().removeClass('alert-danger alert-success').hide();

    if (!phoneCodeWasSent) {
        sendChangingRequest(window.phoneChangingCodeUrl, {
            phone: phoneInput.val(),
        }).then(response => {
            if (response.errors) {
                phoneAlert.addClass('alert-danger');

                response.errors.forEach(function (error) {
                    phoneAlert.append(`<span>${error}</span>`)
                });
                phoneAlert.show();
            } else {
                phoneCodeWasSent = true;
                phoneAlert.addClass('alert-success').append(`<span>${response.message}</span>`).show();
                phoneInput.prop('readonly', true);

                codeInput.parent().show();
            }
        });
    } else {
        sendChangingRequest(window.phoneChangingUrl, {
            phone: phoneInput.val(),
            code: codeInput.val()
        }).then(response => {
            if (response.errors) {
                phoneAlert.addClass('alert-danger');

                response.errors.forEach(function (error) {
                    phoneAlert.append(`<span>${error}</span>`)
                });
                phoneAlert.show();
            } else {
                window.location.reload();
            }
        });
    }
});

$('#emailChangingTrigger').click(function () {
    let emailInput = $('#cabinet-email');
    let codeInput = $('#emailCode');
    let emailAlert = $('#emailAlert');

    emailAlert.empty().removeClass('alert-danger alert-success').hide();

    if (!emailCodeWasSent) {
        sendChangingRequest(window.emailChangingCodeUrl, {
            email: emailInput.val(),
        }).then(response => {
            if (response.errors) {
                emailAlert.addClass('alert-danger');

                response.errors.forEach(function (error) {
                    emailAlert.append(`<span>${error}</span>`)
                });
                emailAlert.show();
            } else {
                emailCodeWasSent = true;
                emailAlert.addClass('alert-success').append(`<span>${response.message}</span>`).show();
                emailInput.prop('readonly', true);

                codeInput.parent().show();
            }
        });
    } else {
        sendChangingRequest(window.emailChangingUrl, {
            email: emailInput.val(),
            code: codeInput.val()
        }).then(response => {
            if (response.errors) {
                emailAlert.addClass('alert-danger');

                response.errors.forEach(function (error) {
                    emailAlert.append(`<span>${error}</span>`)
                });
                emailAlert.show();
            } else {
                window.location.reload();
            }
        });
    }
});

async function sendChangingRequest(url, data) {
    const response = await fetch(url, {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json'
        },
        redirect: 'follow',
        referrerPolicy: 'no-referrer',
        body: JSON.stringify(data)
    });

    return await response.json();
}
