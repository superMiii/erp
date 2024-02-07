$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside2";
    var column = $("#serverside thead tr th").length;
    var d_from_ = $("#d_from").val();
    var d_to_ = $("#d_to").val();
    var hsup = $("#hsup").val();
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_supplier: $("#i_supplier").val(),
    };
    datatable3(controller, column, params, d_from_, d_to_, hsup);
	$("#i_supplier").select2({
        dropdownAutoWidth: true,
        width: "100%",
        /* containerCssClass: "select-sm", */
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_supplier0",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_area: $('#i_area').val(),
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