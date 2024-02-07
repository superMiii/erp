$(document).ready(function() {
    var controller = $("#path").val();
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