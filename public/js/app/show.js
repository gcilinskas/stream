
jQuery(document).ready(function () {
    hideNav();
    params = getParamsByUrl(window.location.href)
    console.log(params);
    loadComments(params.movie);
    initiateSubmit();
});

function initiateSubmit() {
    $("#form-comment").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(result)
            {
                var form = document.getElementById("form-comment");
                form.reset();
                loadComments(result.movie.id);
            }
        });
    });
}

function hideNav() {
    var header = jQuery("#main-header");
    header.css('opacity', 0);
    $('#wrapmain').on("mousewheel", function() {
        var header = jQuery("#main-header"),
            yOffset = 0,
            triggerPoint = 80;
        yOffset = $(document).scrollTop()
        if (yOffset >= triggerPoint) {
            header.css('opacity', 1);
            header.addClass("menu-sticky animated slideInDown");
        } else {
            header.removeClass("menu-sticky animated slideInDown");
            header.css('opacity', 0);
        }
    });
}

function loadComments(id)
{
    $.ajax({
        type: "GET",
        url: 'http://localhost:8005/api/comment/movie/'+id,
        success: function(data)
        {
            $('#movie-comment-container').html("<div></div>");
            data.forEach(function (result) {
                var html = $("<div class='iq-card col-12' style='margin:10px; width: 100%;'>"+
                    "<div class='iq-card-header col-12 d-flex justify-content-between'>" +
                    "<div class='col-12' style='display: flex; justify-content: center'>" +
                    "<div class='iq-card  col-12 iq-card-block iq-card-stretch iq-card-height iq-mb-3'" +
                    "style='border: 1px solid grey; border-radius: 1px; padding:10px; '>" +
                    "<div class='iq-card-body' style='overflow: hidden'>" +
                    "<h6 class='card-title'>"+ result.user.email +"</h6>" +
                    "<p class='card-text'>" + result.text + "</p>" +
                    "<p class='card-text'><small class='text-muted'>" + convertTimestampToDate(result.createdAtTimestamp) + "</small></p>"+
                    "</div> </div> </div> </div></div>");

                $('#movie-comment-container').prepend(html);
                $('#comment-count').html("Komentarai: ("+data.length+")");
            });
        }
    });
}

function getParamsByUrl(url) {
    var urlData = [];
    var keys = [];
    var response = {};

    if (url.indexOf("/show/") > -1) {
        urlData = url.match(/show\/([^ ]*)/);
        urlData = urlData[urlData.length - 1].split('/');
        keys = ['movie'];
    }

    for (i = 0; i < urlData.length; ++i) {
        response[keys[i]] = urlData[i];
    }

    return response;
}

function convertTimestampToDate(timtestamp) {
    const milliseconds = timtestamp * 1000;
    const dateObject = new Date(milliseconds);
    return dateObject.toLocaleString();
}
