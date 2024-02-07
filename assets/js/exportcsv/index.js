$(document).ready(function() {
    $("#i_nota_mulai").select2({
        dropdownAutoWidth: true,
        width: '100%',
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_nota_mulai",
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

    $("#i_nota_akhir").select2({
        dropdownAutoWidth: true,
        width: '100%',
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_nota_akhir",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_nota_mulai: $("#i_nota_mulai").val()
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
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var linkexport = $("#path").val() + "/export_csv/" + $("#i_nota_mulai").val() + '/' + $("#i_nota_akhir").val();
    var column = $("#serverside thead tr th").length;
    var params = {
        i_nota_mulai: $("#i_nota_mulai").val(),
        i_nota_akhir: $("#i_nota_akhir").val(),
    };
    var right = [0];
    datatableexportparams(controller, column, linkexport, params, right);
});