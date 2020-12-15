import {url} from '../config.js';

$(document).on("click", "[data-entry-id]", function () {
    var movieId = $(this).data('entryId');
    getDescription(movieId);

    function getDescription(movie) {
        $.ajax({
            url: url + '/movie/' + movie,
            type: 'GET',
            success: function (response) {
                var desc = response.description;
                $('#read-more-modal-description').empty();
                $('#read-more-modal-description').html(desc);
            },
            error: function (xhr, status, error) {
                alert('Nesuveike filmo apziura, kreipkites i administratoriu');
            }
        });
    }
});
