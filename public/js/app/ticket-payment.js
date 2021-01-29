import {url} from '../config.js';

$( document ).ready(function() {
    initiateSubmitButtons();
});

function initiateSubmitButtons() {
    $('.pay-for-ticket').submit(function(e) {
        e.preventDefault();
    });

    $(".pay-for-ticket").click(function(e) {
        var movieId = $(this).find('input').first().val();
        payForTicket(movieId);
    });
}

function payForTicket(movieId) {
    $.ajax({
        type: "POST",
        url: url + '/paysera/new/' + movieId,
        success: function(data) {
            if (data.url === undefined) {
                alert('Tik registruoti vartotojai gali pirkti bilietus')
                window.location.href = url + '/register'
            } else {
                window.location.href = data.url;
            }
        },
        error: function (xhr) {
            alert((xhr.responseText));
        }
    });
}
