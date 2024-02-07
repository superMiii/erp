$(document).ready(function() {
    var controller = $("#path").val();

    $("#isuppliergroup").select2({
        placeholder:  $("#textcari").val(),
        width: "100%",
        allowClear: true,
        ajax: {
            url: base_url + controller + "/get_group_supplier",
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
    /* $("#submit").on("click", function () {
    	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();

    	var form = $('.form-validation').jqBootstrapValidation();
    	if (form) {
    		sweetadd(controller);
    	}
    }); */
});