$(document).ready(function() {
    var controller = $("#path").val();
    $(".select2").select2();   
    $("#iproduct").select2({
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_produk",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term
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
    }).change(function(event) {
        $.ajax({
            type: "post",
            data: {
                namabarang : $(this).val(),
            },
            url: base_url + $("#path").val() + "/get_namaproduk",
            dataType: "json",
            success: function(data) {
                $("#ebr").val(data['header'][0]['e_product_name']);
            },
            error: function() {
                swal("500 internal server error : (");
            },
        });
    });
   
    $("#istorloc").select2({
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_storloc",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    gudang : $('#igudang').val(),
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
    
    

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetaddv33sup($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#hsup").val(), formData);
        }
    });

});