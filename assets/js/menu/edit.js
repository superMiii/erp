$(document).ready(function() {
    var controller = $("#path").val();
    $("#iparent").select2({
        placeholder: "Select Parent",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_menu",
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
    $("#ipower").select2({
        placeholder: "Select Power",
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_power",
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
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        var formData = new FormData(this);
        if (formData) {
            sweeteditv2(controller, formData);
        }
    });
});