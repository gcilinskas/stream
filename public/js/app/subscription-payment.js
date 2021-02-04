import {url} from '../config.js';

$( document ).ready(function() {
    initiateSubmitButtons();
});

function initiateSubmitButtons() {
    $('.pay-for-subscription').submit(function(e) {
        e.preventDefault();
    });

    $(".pay-for-subscription").click(function(e) {
        payForSubscription();
    });
}

function payForSubscription() {
    $.ajax({
        type: "POST",
        url: url + '/paysera/buy/subscription',
        success: function(data) {
            if (data.url === undefined) {
                alert('Tik registruoti vartotojai gali pirkti prenumeratas')
                window.location.href = url + '/register'
            } else {
                window.location.href = data.url;
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
}
