$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var linkadd = $("#path").val() + "/indexx/" + $("#d_from").val() + "/" + $("#d_to").val() + "/" + $("#harea").val();
    var column = $("#serverside thead tr th").length;
    var id_menu = $("#id_menu").val();
    /* var color       = $("#color").val(); */
    var right = [8, 9];
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_area: $("#i_area").val(),
    };
    if (id_menu != "") {
        datatableaddparams(controller, column, linkadd, params, right);
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
    } else {
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
    }
});

function refreshview(link) {
    // window.location = link;
    window.location = link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val() + '/' + $("#harea").val();
}