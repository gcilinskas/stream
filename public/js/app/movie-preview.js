import {url} from '../config.js';

// $(document).on("click", "[data-entry-id]", function () {
//     var movieId = $(this).data('entryId');
//     getDescription(movieId);
//
//     function getDescription(movie) {
//
//         var xmlhttp;
//         if (window.XDomainRequest) {
//             xmlhttp = new XDomainRequest();
//
//             xmlhttp.onload = function() {
//                 console.log(xmlhttp.responseText);
//                 document.getElementById("read-more-modal-description").innerHTML =
//                     JSON.parse(xmlhttp.responseText).description;
//             };
//         } else if (window.XMLHttpRequest) {
//             xmlhttp = new XMLHttpRequest();
//         } else {
//             xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
//         }
//
//         xmlhttp.onreadystatechange = function() {
//             if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
//                 console.log(xmlhttp.responseText);
//                 document.getElementById("read-more-modal-description").innerHTML =
//                     JSON.parse(xmlhttp.responseText).description;
//             }
//         }
//
//         xmlhttp.open("GET", url + '/movie/' + movie, true);
//         xmlhttp.send();
//     }
// });
// });

function readMore(movie) {
    var xmlhttp;
    if (window.XDomainRequest) {
        xmlhttp = new XDomainRequest();

        xmlhttp.onload = function() {
            console.log(xmlhttp.responseText);
            document.getElementById("read-more-modal-description").innerHTML =
                JSON.parse(xmlhttp.responseText).description;
        };
    } else if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            console.log(xmlhttp.responseText);
            document.getElementById("read-more-modal-description").innerHTML =
                JSON.parse(xmlhttp.responseText).description;
        }
    }

    xmlhttp.open("GET", url + '/movie/' + movie, true);
    xmlhttp.send();
}
