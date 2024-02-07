$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var linkadd = $("#path").val() + "/indexx";
    var column = $("#serverside thead tr th").length;
    var id_menu = $("#id_menu").val();
    /* var color       = $("#color").val(); */
    var d_from_ = $("#d_from").val();
    var d_to_ = $("#d_to").val();
    var harea = $("#harea").val();
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_area: $("#i_area").val(),
    };
    var right = [7, 8];
    if (id_menu != "") {
        datatableadd3(controller, column, linkadd, params, right, d_from_, d_to_, harea);
    }
    else {
        datatable3(controller, column, params, d_from_, d_to_, harea);
    }

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

function refreshview(link) {
    window.location = link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#harea").val();
}