$(document).ready(function () {
	pickdatetabel();
	var controller  = $("#path").val() + "/serverside";
	var linkadd     = $("#path").val() + "/add";
	var column      = $("#serverside thead tr th").length;
	var id_menu     = $("#id_menu").val();
	var color       = $("#color").val();
	var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
    };
	if (id_menu != "") {
		datatableaddparams(controller, column, linkadd, params)
	} else {
		datatable(controller, column);
	}

});
