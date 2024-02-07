$(document).ready(function() {
    var controller = $("#path").val();
    $(".select2").select2();
    $("#i_product_category").change(function(event) {
        $('#i_product_subcategory').val("");
        $('#i_product_subcategory').html("");
    });
    $("#i_product_subcategory").select2({
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_subcategory",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_product_category: $("#i_product_category").val(),
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
            sweetedit(controller, formData);
        }
    });
});