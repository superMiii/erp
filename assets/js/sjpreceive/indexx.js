$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serversidex";
    var column = $("#serverside thead tr th").length;
    var id_menu = $("#id_menu").val();
    /* var color       = $("#color").val(); */
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
    };
    if (id_menu != "") {
        datatableparams(controller, column, params);
    }
    else {
        datatableparams(controller, column, params);
    }
});