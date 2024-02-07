$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside2";
    var column = $("#serverside thead tr th").length;
    var d_from_ = $("#d_from").val();
    var d_to_ = $("#d_to").val();
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
    };
    datatableparams(controller, column, params);
    // datatablelink(controller, column, params, d_from_,d_to_);
});