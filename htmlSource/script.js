$(document).ready(function () {
    // paginator add active and doted
    var url = location.href.split('/');
    var cur_pag = url[url.length - 2];
    if (location.pathname != '/site/index.html' && location.pathname != '/site/') {
        var id_page = cur_pag.substr(1);
    } else {
        var id_page = 1;
        var cur_pag = 'p1';
    }
    $('#' + cur_pag).addClass('active');
    var pag_end = $('.pagination li:last-child').attr('id').substr(1);
    for (var i = 1; i <= pag_end; i++) {
        if ((i != 1 && i != pag_end) && (id_page < i - 2 || id_page > i + 2)) {
            $('#p' + i).hide();
        } else {
            if (id_page < 5) {
                if (i == pag_end) {
                    $('#p' + pag_end).before('<li><a>...</a></li>');
                }
            } else if (id_page >= 5 && id_page <= pag_end - 4) {
                if (i == 1) {
                    $('#p' + i).after('<li><a>...</a></li>');
                }
                if (i == pag_end) {
                    $('#p' + pag_end).before('<li><a>...</a></li>');
                }

            } else {
                if (i == 1) {
                    $('#p1').after('<li><a>...</a></li>');
                }
            }

        }
    }

    $('#gallery-wrapper').reloadSlider();

});