$(document).ready(function() {
	pickdatetabel();
    var controller  = $("#path").val() + "/serverside";
	var linkadd     = $("#path").val() + "/add";
	var column      = $("#serverside thead tr th").length;
	var id_menu     = $("#id_menu").val();
	var color       = $("#color").val();
	var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_supplier: $("#i_supplier").val(),
    };
	if (id_menu != "") {
		//datatableadd(controller, column, linkadd, color);
		datatableaddparams(controller, column, linkadd, params)
	} else {
		datatable(controller, column);
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