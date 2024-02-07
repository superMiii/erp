$(document).ready(function() {
    number();
    $('#d_document').change(function() {
        number();
    });

    $("#i_area").select2({
        dropdownAutoWidth: true,
            width: "100%",
            containerCssClass: "select-sm",
            allowClear: true,
            ajax: {
                url: base_url + $("#path").val() + "/get_area",
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
    }).change(function() {
        $("#i_store_loc").val('');
        $("#i_store_loc").html('');
    });


    $("#i_store_loc").select2({
        width: "100%",
        dropdownAutoWidth: true,
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_store",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    'i_area': $('#i_area').val(),
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

function number() {
    $.ajax({
        type: "post",
        data: {
            'tanggal': $('#d_document').val(),
        },
        url: base_url + $("#path").val() + '/number ',
        dataType: "json",
        success: function(data) {
            $('#i_document').val(data);
        },
        error: function() {
            Swal.fire({
                type: "error",
                title: g_maaf,
                text: "Error data",
                confirmButtonClass: "btn btn-danger",
            });
        }
    });
}



$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
$('form').on('submit', function(e) { 
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweetaddv3($("#path").val(), $("#d_from").val(), $("#d_to").val(), formData);   
        }
});

