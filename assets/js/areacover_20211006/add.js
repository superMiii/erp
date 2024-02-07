$(document).ready(function() {
    var controller = $("#path").val();
    $('.skin-line input').each(function() {
        var self = $(this),
            label = self.next(),
            label_text = label.text();

        label.remove();
        self.iCheck({
            checkboxClass: 'icheckbox_line-blue',
            radioClass: 'iradio_line-blue',
            insert: '<div class="icheck_line-icon"></div>' + label_text
        });

        $(this).on('ifChanged', function() {
            var _this = $(this);
            if (_this.is(':checked')) {
                $("#i_city").val('');
                $("#i_city").html('');
                $("#i_city").prop('required', false);
                $("#i_city").prop('disabled', true);
            } else {
                $("#i_city").prop('required', true);
                $("#i_city").prop('disabled', false);
            }
        });
    });

    $("#i_area").select2({
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
    }).change(function(event) {
        $('#i_city').val("");
        $('#i_city').html("");
    });

    $("#i_city").select2({
        placeholder: $("#textcarikota").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_city",
            dataType: "json",
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    i_area: $("#i_area").val(),
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

    $("#f_all_city").click(function() {
        alert("Handler for .click() called.");
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