$(document).ready(function() {
    $('.select2').select2({
        width: "100%",
    });
    var controller = $("#path").val() + "/serverside";
    var linkadd = $("#path").val() + "/add";
    var column = $("#serverside thead tr th").length;
    var i_menu = $("#i_menu").val();
    var params = {
        month: $("#month").val(),
        year: $("#year").val(),
    };
    var right = [3];
    if (i_menu != "") {
        datatableaddparams(controller, column, linkadd, params, right);
    }
    else {
        datatableparams(controller, column, params);
    }
});