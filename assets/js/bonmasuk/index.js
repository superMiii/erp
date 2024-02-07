$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var linkadd = $("#path").val() + "/add";
    var column = $("#serverside thead tr th").length;
    var i_menu = $("#i_menu").val();
    /* var color = $("#color").val(); */
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
    };
    if (i_menu != "") {
        datatableaddparams(controller, column, linkadd, params);
    }
    else {
        datatableparams(controller, column, params);
    }
});