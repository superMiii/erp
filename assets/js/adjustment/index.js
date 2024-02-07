$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var linkadd = $("#path").val() + "/add";
    var column = $("#serverside thead tr th").length;
    var i_menu = $("#i_menu").val();
    /* var color = $("#color").val(); */
    var d_from_ = $("#d_from").val();
    var d_to_ = $("#d_to").val();
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_store: $("#i_store").val(),
    };
    var right = [];
    if (i_menu != "") {
        // datatableaddparams(controller, column, linkadd, params);
        datatableaddlink(controller, column, linkadd, params, right,d_from_,d_to_);
    } else {
        // datatableparams(controller, column, params);
        datatablelink(controller, column, params, d_from_,d_to_);
    }
    $("#i_store").select2({
        dropdownAutoWidth: true,
        width: "100%",
        /* containerCssClass: "select-sm", */
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_store0",
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
    // window.location = link;
    window.location = link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val();
}