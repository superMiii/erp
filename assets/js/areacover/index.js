$(document).ready(function() {

    var controller = $("#path").val() + "/serverside";
    var linkadd = $("#path").val() + "/add";
    var column = $("#serverside thead tr th").length;
    var i_menu = $("#i_menu").val();
    var color = $("#color").val();
    if (i_menu != "") {
        datatableadd(controller, column, linkadd, color);
    } else {
        datatable(controller, column);
    }

});