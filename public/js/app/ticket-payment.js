$('.pay-for-ticket').submit(function(e) {
    e.preventDefault();
});

$(".pay-for-ticket").click(function(e) {
    var movieId = $(this).find('input').first().val();
    payForTicket(movieId);
});

$(".open-ticket-apply-modal").click(function() {
    $("#open-ticket-modal-form").submit();
});

$("#open-ticket-modal-form").submit(function(e) {
    e.preventDefault();
    var movieId = $(this).find('input').first().val();
    loadTickets(movieId);
});

$("#apply-ticket-submit").click(function () {
    $("#apply-ticket-form").submit();
})

$("#apply-ticket-form").submit(function (e) {
    e.preventDefault();

    console.log('submit')
    var ticket = $(this).find('select').val();
    console.log(ticket);
    useTicket(ticket);
});

function useTicket(ticket) {
    $.ajax({
        type: "POST",
        url: 'http://localhost:8005/ticket/use/' + ticket,
        success: function(data) {
            window.location.href = 'http://localhost:8005/show/' + data;
        },
        error: function (xhr) {
        }
    });
}

function payForTicket(movieId) {
    $.ajax({
        type: "POST",
        url: 'http://localhost:8005/api/paysera/new/' + movieId,
        success: function(data) {
            if (data.url === undefined) {
                alert('Tik registruoti vartotojai gali pirkti bilietus')
                window.location.href = 'http://localhost:8005/register'
            } else {
                window.location.href = data.url;
            }
        },
        error: function (xhr) {
            alert((xhr.responseText));
        }
    });
}

function loadTickets(movie) {
    $.ajax({
        url: 'http://localhost:8005/ticket/movie/' + movie,
        type: 'GET',
        success: function (html) {
            var array = [];
            $('#apply-ticket-select').empty();
            $.each(html, function (i, item) {
                array.push(`<option value="${ item.id }">Kodas: ${ item.code } | ${ item.title }</option>`);
            });
            $('#apply-ticket-select').append(array);
        },
        error: function (xhr, status, error) {
            alert('Get restaurant list is failed\n' +  xhr.responseText);
        }
    });
}





