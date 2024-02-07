$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serversidex";
    var column = $("#serverside thead tr th").length;
    var id_menu = $("#id_menu").val();
    var d_from_ = $("#d_from").val();
    var d_to_ = $("#d_to").val();
    var harea = $("#harea").val();
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
    };
    datatable3(controller, column, params, d_from_, d_to_, harea);
});