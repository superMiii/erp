$(document).ready(function() {
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var column = $("#serverside thead tr th").length;
    var params = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_supplier: $("#i_supplier").val(),
    };
    datatableparams(controller, column, params);
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


$("#submit").click(function(event) {
    if ($("#formclose input:checkbox:checked").length > 0){
        return true;
    }else{
        swal('Pilih data minimal satu!');
        return false;
    }
});
 
$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});