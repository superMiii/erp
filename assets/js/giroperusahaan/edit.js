$(document).ready(function() {
    $("#i_supplier").select2({
        dropdownAutoWidth: true,
        width: "100%",
        containerCssClass: "select-sm",
        allowClear: true,
        ajax: {
            url: base_url + $("#path").val() + "/get_supplier",
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

    $("#i_document").keyup(function() {
        cek_kode();
    });

    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $("form").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweeteditv33($("#path").val(), $("#d_from").val(), $("#d_to").val(), $("#hsup").val(), formData);
        }
    });
});

function cek_kode() {
    $.ajax({
        type: "post",
        data: {
            i_document: $('#i_document').val(),
            i_document_old: $('#i_document_old').val(),
        },
        url: base_url + $("#path").val() + "/cek_code_edit",
        dataType: "json",
        success: function(data) {
            if (data == 1) {
                $("#ada").attr("hidden", false);
                $("#submit").attr("disabled", true);
            } else {
                $("#ada").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        /* error: function() {
            Swal.fire("Error :)");
        }, */
    });
}