$(document).ready(function() {
    $(".select2").select2();
    pickdatetabel();
    var controller = $("#path").val() + "/serverside";
    var column = $("#serverside thead tr th").length;
    var parameter = {
        dfrom: $("#dfrom").val(),
        dto: $("#dto").val(),
        i_product: $("#i_product").val(),
    };
    var right = [];
    // datatableinfoparams(controller, column, parameter, right);
    $("#i_product").select2({
        dropdownAutoWidth: true,
        width: "100%",
        /* containerCssClass: "select-sm", */
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_prod",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
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


function exportexcel() {
    var dfrom = document.getElementById('dfrom').value;
    var dto = document.getElementById('dto').value;
    var i_product = document.getElementById('i_product').value;

    if (dfrom == '') {
        alert('Pilih Tanggal Terlebih Dahulu!!!');
        return false;
    } else {
        var abc =  base_url + $("#path").val() + "/export/" + dfrom + "/" + dto + "/" + i_product;
        $("#href").attr("href", abc);
        return true;
    }
}
