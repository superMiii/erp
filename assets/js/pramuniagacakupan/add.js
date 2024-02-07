$(document).ready(function() {
    $('.datepicker').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "id",
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true,
        datesDisabled: ['08/06/2021', '08/21/2021']
    });
    var controller = $("#path").val();
    $("#i_area_cover").select2({
        placeholder: $("#textcari").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_area",
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

    $("#i_salesman").select2({
        placeholder: $("#textcarisales").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_salesman",
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
            sweetadd(controller, formData);
        }
    });
});