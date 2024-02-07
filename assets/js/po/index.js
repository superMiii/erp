$(document).ready(function() {
	pickdatetabel();
    var controller  = $("#path").val() + "/serverside";
	var linkadd     = $("#path").val() + "/add/" + $("#d_from").val() + "/" + $("#d_to").val();
	var column      = $("#serverside thead tr th").length;
	var id_menu     = $("#id_menu").val();
	var color       = $("#color").val();
    var d_from_ = $("#d_from").val();
    var d_to_ = $("#d_to").val();
	var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_supplier: $("#i_supplier").val(),
    };
	if (id_menu != "") {
		//datatableadd(controller, column, linkadd, color);
		// datatableaddparams(controller, column, linkadd, params)
        datatableaddlink(controller, column, linkadd, params, d_from_,d_to_);
	} else {
		// datatable(controller, column);
        datatablelink(controller, column, d_from_,d_to_);
	}
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

function refreshview(link) {
    window.location = link + '/index/' + $("#d_from").val() + '/' + $("#d_to").val();
}