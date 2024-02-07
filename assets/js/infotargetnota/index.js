$(document).ready(function() {
    $(".select2").select2();
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var column = 10;
    var parameter = {
        year: $("#year").val(),
        month: $("#month").val(),
        i_area: $("#i_area").val(),
        // i_salesman: $("#i_salesman").val(),
    };
    var right = [3, 4, 5, 6, 7,8,9];
    datatableinfoparams(controller, column, parameter, right);
    $("#i_area").select2({
        dropdownAutoWidth: true,
        width: "100%",
        /* containerCssClass: "select-sm", */
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_area",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                };
                return query;
            },
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    // $("#i_salesman").select2({
    //     dropdownAutoWidth: true,
    //     width: "100%",
    //     /* containerCssClass: "select-sm", */
    //     allowClear: true,
    //     ajax: {
    //         url: base_url + $("#path").val() + "/get_salesman",
    //         dataType: "json",
    //         delay: 250,
    //         data: function(params) {
    //             var query = {
    //                 q: params.term,
    //                 i_area: $('#i_area').val(),
    //             };
    //             return query;
    //         },
    //         processResults: function(data) {
    //             return {
    //                 results: data,
    //             };
    //         },
    //         cache: false,
    //     },
    // });
    $(".export").click(function() {
        $.ajax({
            beforeSend: function() {
                $(".app-content").block({
                    message: '<div class="spinner-grow text-primary"></div><div class="spinner-grow text-success"></div><div class="spinner-grow text-teal"></div><div class="spinner-grow text-info"></div><div class="spinner-grow text-warning"></div><div class="spinner-grow text-orange"></div><div class="spinner-grow text-danger"></div><div class="spinner-grow text-secondary"></div><div class="spinner-grow text-dark"></div><div class="spinner-grow text-muted"></div><br><h1 class="text-muted d-block">P l e a s e &nbsp;&nbsp; W a i t</h1>',
                    centerX: false,
                    centerY: false,
                    overlayCSS: {
                        backgroundColor: "#fff",
                        opacity: 0.8,
                        cursor: "wait",
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: "none",
                    },
                });
            },
            complete: function() {
                $('.app-content').unblock();
            },
            type: "post",
            data: parameter,
            cache: false,
            /* async: false, */
            url: base_url + $("#path").val() + "/export",
            dataType: "json",
            success: function(data) {
                $('.app-content').unblock();
                var $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", data.nama_file);
                $a[0].click();
                $a.remove();
            }
        });
    });
});