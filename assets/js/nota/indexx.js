$(document).ready(function() {
    pickdatetabel();    
    cek0();
    var controller = $("#path").val() + "/serversidex";
    var column = $("#serverside thead tr th").length;
    var id_menu = $("#id_menu").val();
    /* var color       = $("#color").val(); */
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_area: $("#i_area").val(),
    };
        datatableparams(controller, column, params);
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
});

function cek0() {
    $.ajax({
        type: "post",
        // data: {
        //     'tanggal': $('#d_document').val(),
        //     'i_area': $('#i_area').val(),
        //     'i_rv_type': $('#i_rv_type').val(),
        // },
        url: base_url + $("#path").val() + '/cek0 ',
        dataType: "json",
        // success: function(data) {
        //     $('#i_document').val(data);
        // },
        error: function() {
            Swal.fire({
                type: "error",
                title: g_maaf,
                confirmButtonClass: "btn btn-danger",
            });
        }
    });
}