$(document).ready(function() {
    window.onbeforeprint = function() {
        opener.window.refreshview($('#path').val());
        /* window.location = $('#path').val(); */
        setTimeout(window.close, 0);
        // $.ajax({
        //     type: "POST",
        //     url: $('#path').val() + '/update_print',
        //     data: {
        //         'id': $('#id').val(),
        //     },
        //     success: function(data) {
        //         opener.window.refreshview($('#path').val());
        //         /* window.location = $('#path').val(); */
        //         setTimeout(window.close, 0);
        //     },
        //     error: function(XMLHttpRequest) {
        //         alert('fail');
        //     }
        // });
    }
});